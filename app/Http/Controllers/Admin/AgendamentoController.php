<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Agendamento;
use App\Models\Clinic;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class AgendamentoController extends Controller
{
    public function index(Request $request)
    {
        $clinicId = app()->bound('clinic_id') ? app('clinic_id') : null;
        $date = $request->query('date', Carbon::today()->format('Y-m-d'));
        $range = $request->query('range', 0);
        $start = Carbon::parse($date);
        $end = $start->copy()->addDays($range);
        $professionals = $clinicId ? $this->professionalsForDate($clinicId, $date) : [];

        $horarios = [];
        $startTime = Carbon::createFromTime(0, 0);
        $endTime = Carbon::createFromTime(23, 30);
        for ($time = $startTime->copy(); $time <= $endTime; $time->addMinutes(30)) {
            $horarios[] = $time->format('H:i');
        }

        $agenda = [];
        if ($clinicId && $professionals) {
            $profIds = array_column($professionals, 'id');

            $cacheKey = "agendamentos_{$clinicId}_{$start->format('Y-m-d')}_{$end->format('Y-m-d')}";
            $agendamentos = Cache::remember(
                $cacheKey,
                60,
                function () use ($clinicId, $start, $end, $profIds) {
                    return Agendamento::with(['paciente.pessoa'])
                        ->where('clinica_id', $clinicId)
                        ->whereBetween('data', [$start, $end])
                        ->whereIn('profissional_id', $profIds)
                        ->where('status', '!=', 'lista_espera')
                        ->orderBy('data')
                        ->get();
                }
            );

            foreach ($agendamentos as $ag) {
                $pessoa = optional($ag->paciente)->pessoa;
                $start = Carbon::parse($ag->hora_inicio);
                $end = Carbon::parse($ag->hora_fim);
                $rowStart = $start->copy()->minute($start->minute >= 30 ? 30 : 0);
                $hora = $rowStart->format('H:i');
                $rowspan = max(1, intdiv($start->diffInMinutes($end), 15));

                $agenda[$ag->profissional_id][$hora][] = [
                    'id' => $ag->id,
                    'data' => Carbon::parse($ag->data)->format('Y-m-d'),
                    'hora_inicio' => $start->format('H:i'),
                    'hora_fim' => $end->format('H:i'),
                    'paciente_id' => $ag->paciente_id,
                    'paciente' => $pessoa ? trim(($pessoa->primeiro_nome ?? '') . ' ' . ($pessoa->ultimo_nome ?? '')) : '',
                    'observacao' => $ag->observacao ?? '',
                    'status' => $ag->status ?? 'pendente',
                    'rowspan' => $rowspan,
                ];

                for ($t = $rowStart->copy()->addMinutes(30); $t < $end; $t->addMinutes(30)) {
                    $agenda[$ag->profissional_id][$t->format('H:i')][] = ['skip' => true];
                }
            }
        }

        return view('agendamentos.index', compact('professionals', 'horarios', 'agenda', 'date'));
    }

    protected function professionalsForDate(int $clinicId, string $date): array
    {
        $carbon = Carbon::parse($date);
        $weekStart = $carbon->copy()->startOfWeek(Carbon::MONDAY)->toDateString();
        $day = $carbon->isoWeekday();

        $ids = \App\Models\EscalaTrabalho::where('clinica_id', $clinicId)
            ->whereDate('semana', $weekStart)
            ->where('dia_semana', $day)
            ->pluck('profissional_id')
            ->unique()
            ->toArray();

        if (empty($ids)) {
            return [];
        }

        $profissionais = \App\Models\Profissional::with('pessoa')
            ->get()
            ->filter(fn($p) => in_array($p->id, $ids));

        return $profissionais
            ->map(function ($prof) {
                $gender = $prof->pessoa->sexo ?? null;
                $prefix = $gender === 'Masculino' ? 'Dr. ' : ($gender === 'Feminino' ? 'Dra. ' : '');
                return [
                    'id' => $prof->id,
                    'name' => $prefix . ($prof->pessoa->primeiro_nome ?? ''),
                ];
            })
            ->values()
            ->toArray();
    }

    public function professionals(Request $request)
    {
        $clinicId = app()->bound('clinic_id') ? app('clinic_id') : null;
        $date = $request->query('date');

        if (! $clinicId || ! $date) {
            return response()->json(['professionals' => [], 'agenda' => []]);
        }

        $range = $request->query('range', 0);
        $start = Carbon::parse($date);
        $end = $start->copy()->addDays($range);

        $professionals = $this->professionalsForDate($clinicId, $date);
        $agenda = [];

        if ($professionals) {
            $profIds = array_column($professionals, 'id');

            $cacheKey = "agendamentos_{$clinicId}_{$start->format('Y-m-d')}_{$end->format('Y-m-d')}";
            $agendamentos = Cache::remember(
                $cacheKey,
                60,
                function () use ($clinicId, $start, $end, $profIds) {
                    return Agendamento::with(['paciente.pessoa'])
                        ->where('clinica_id', $clinicId)
                        ->whereBetween('data', [$start, $end])
                        ->whereIn('profissional_id', $profIds)
                        ->where('status', '!=', 'lista_espera')
                        ->orderBy('data')
                        ->get();
                }
            );

            foreach ($agendamentos as $ag) {
                $pessoa = optional($ag->paciente)->pessoa;
                $start = Carbon::parse($ag->hora_inicio);
                $end = Carbon::parse($ag->hora_fim);
                $rowStart = $start->copy()->minute($start->minute >= 30 ? 30 : 0);
                $hora = $rowStart->format('H:i');
                $rowspan = max(1, intdiv($start->diffInMinutes($end), 15));

                $agenda[$ag->profissional_id][$hora][] = [
                    'id' => $ag->id,
                    'data' => Carbon::parse($ag->data)->format('Y-m-d'),
                    'hora_inicio' => $start->format('H:i'),
                    'hora_fim' => $end->format('H:i'),
                    'paciente_id' => $ag->paciente_id,
                    'paciente' => $pessoa ? trim(($pessoa->primeiro_nome ?? '') . ' ' . ($pessoa->ultimo_nome ?? '')) : '',
                    'observacao' => $ag->observacao ?? '',
                    'status' => $ag->status ?? 'pendente',
                    'rowspan' => $rowspan,
                ];

                for ($t = $rowStart->copy()->addMinutes(30); $t < $end; $t->addMinutes(30)) {
                    $agenda[$ag->profissional_id][$t->format('H:i')][] = ['skip' => true];
                }
            }
        }

        return response()->json([
            'professionals' => $professionals,
            'agenda' => $agenda,
        ]);
    }

    public function consultasDia(Request $request)
    {
        $clinicId = app()->bound('clinic_id') ? app('clinic_id') : null;
        $date = $request->query('date');

        if (! $clinicId || ! $date) {
            return response()->json(['consultas' => []]);
        }

        $range = $request->query('range', 0);
        $start = Carbon::parse($date);
        $end = $start->copy()->addDays($range);

        $consultas = Agendamento::with(['paciente.pessoa', 'profissional.pessoa'])
            ->where('clinica_id', $clinicId)
            ->whereBetween('data', [$start, $end])
            ->whereIn('status', ['pendente', 'cancelado', 'confirmado'])
            ->orderBy('data')
            ->get()
            ->map(function ($ag) {
                $paciente = optional($ag->paciente)->pessoa;
                $prof = optional($ag->profissional)->pessoa;
                return [
                    'data' => Carbon::parse($ag->data)->format('Y-m-d'),
                    'hora' => Carbon::parse($ag->hora_inicio)->format('H:i'),
                    'paciente' => $paciente ? trim(($paciente->primeiro_nome ?? '') . ' ' . ($paciente->ultimo_nome ?? '')) : '',
                    'tipo' => $ag->tipo ?? '',
                    'profissional' => $prof ? trim(($prof->primeiro_nome ?? '') . ' ' . ($prof->ultimo_nome ?? '')) : '',
                    'status' => $ag->status ?? '',
                ];
            })
            ->values();

        return response()->json(['consultas' => $consultas]);
    }

    public function waitlist(Request $request)
    {
        $clinicId = app()->bound('clinic_id') ? app('clinic_id') : null;
        $date = $request->query('date');

        if (! $clinicId || ! $date) {
            return response()->json(['waitlist' => []]);
        }

        $range = $request->query('range', 3);
        $start = Carbon::parse($date);
        $end = $start->copy()->modify("+{$range} days");

        $waitlist = Agendamento::with(['paciente.pessoa'])
            ->where('clinica_id', $clinicId)
            ->whereBetween('data', [$start, $end])
            ->where('status', 'lista_espera')
            ->orderBy('data')
            ->get()
            ->map(function ($ag) use ($clinicId, $start, $range) {
                $pessoa = optional($ag->paciente)->pessoa;
                $sugestao = $this->buscarProximoHorarioDisponivel($clinicId, $start->copy(), $range);

                return [
                    'id' => $ag->id,
                    'data' => Carbon::parse($ag->data)->format('Y-m-d'),
                    'paciente' => $pessoa ? trim(($pessoa->primeiro_nome ?? '') . ' ' . ($pessoa->ultimo_nome ?? '')) : '',
                    'contato' => $ag->contato ?? '',
                    'observacao' => $ag->observacao ?? '',
                    'sugestao' => $sugestao,
                ];
            })
            ->values();

        return response()->json(['waitlist' => $waitlist]);
    }

    public function waitlistMonthly(Request $request)
    {
        $clinicId = app()->bound('clinic_id') ? app('clinic_id') : null;
        if (! $clinicId) {
            return response()->json(['waitlist' => []]);
        }

        $month = $request->query('month');
        $year = $request->query('year');
        $date = $request->query('date');

        if ($date) {
            $start = Carbon::parse($date)->modify('first day of this month');
        } else {
            $now = Carbon::parse('now');
            $year = $year ?? $now->format('Y');
            $month = $month ?? $now->format('m');
            $start = Carbon::parse(sprintf('%04d-%02d-01', $year, $month));
        }

        $end = $start->copy()->modify('last day of this month');

        $waitlist = Agendamento::with(['paciente.pessoa'])
            ->where('clinica_id', $clinicId)
            ->where('status', 'lista_espera')
            ->whereBetween('data', [$start, $end])
            ->orderBy('data')
            ->get()
            ->map(function ($ag) {
                $pessoa = optional($ag->paciente)->pessoa;
                return [
                    'id' => $ag->id,
                    'data' => Carbon::parse($ag->data)->format('Y-m-d'),
                    'paciente' => $pessoa ? trim(($pessoa->primeiro_nome ?? '') . ' ' . ($pessoa->ultimo_nome ?? '')) : '',
                    'contato' => $ag->contato ?? '',
                    'observacao' => $ag->observacao ?? '',
                ];
            })
            ->groupBy('data')
            ->map(fn($items) => $items->values());

        return response()->json(['waitlist' => $waitlist]);
    }

    protected function buscarProximoHorarioDisponivel(int $clinicId, Carbon $start, int $range = 3, int $slotMinutes = 15)
    {
        $clinic = Clinic::with('horarios')->find($clinicId);
        if (! $clinic) {
            return null;
        }

        $endDate = $start->copy()->addDays($range);

        for ($date = $start->copy(); $date <= $endDate; $date->addDay()) {
            $diaSemana = $date->isoWeekday();
            $horarioClinic = $clinic->horarios->firstWhere('dia_semana', $diaSemana);
            if (! $horarioClinic) {
                continue;
            }

            $diaInicio = $date->copy()->setTimeFromTimeString($horarioClinic->hora_inicio);
            $diaFim = $date->copy()->setTimeFromTimeString($horarioClinic->hora_fim);

            $agendamentos = Agendamento::where('clinica_id', $clinicId)
                ->whereDate('data', $date->format('Y-m-d'))
                ->where('status', '!=', 'lista_espera')
                ->orderBy('hora_inicio')
                ->get();

            $current = $diaInicio->copy();
            foreach ($agendamentos as $ag) {
                $agStart = Carbon::parse($ag->hora_inicio);
                $agStart->setDate($date->year, $date->month, $date->day);
                $agEnd = Carbon::parse($ag->hora_fim);
                $agEnd->setDate($date->year, $date->month, $date->day);

                if ($current->diffInMinutes($agStart, false) >= $slotMinutes) {
                    return [
                        'data' => $current->format('Y-m-d'),
                        'inicio' => $current->format('H:i'),
                        'fim' => $current->copy()->addMinutes($slotMinutes)->format('H:i'),
                    ];
                }

                if ($agEnd->greaterThan($current)) {
                    $current = $agEnd->copy();
                }
            }

            if ($current->diffInMinutes($diaFim, false) >= $slotMinutes) {
                return [
                    'data' => $current->format('Y-m-d'),
                    'inicio' => $current->format('H:i'),
                    'fim' => $current->copy()->addMinutes($slotMinutes)->format('H:i'),
                ];
            }
        }

        return null;
    }

    public function store(Request $request)
    {
        $clinicId = app()->bound('clinic_id') ? app('clinic_id') : null;
        if (! $clinicId) {
            return response()->json([
                'success' => false,
                'message' => 'Clínica não selecionada.',
                'redirect' => '/admin/clinicas',
            ], 400);
        }

        $data = $request->validate([
            'profissional_id' => 'nullable|exists:profissionais,id',
            'paciente_id' => 'required|exists:pacientes,id',
            'data' => 'required|date',
            'hora_inicio' => 'nullable|required_unless:status,lista_espera',
            'hora_fim' => 'nullable|required_unless:status,lista_espera',
            'tipo' => 'nullable|string',
            'contato' => 'nullable|string',
            'observacao' => 'nullable|string',
            'status' => 'required|in:confirmado,pendente,cancelado,faltou,lista_espera',
        ]);

        $data['profissional_id'] = $data['profissional_id'] ?? null;
        $data['hora_inicio'] = isset($data['hora_inicio']) && $data['hora_inicio']
            ? Carbon::parse($data['hora_inicio'])->format('H:i:s')
            : null;
        $data['hora_fim'] = isset($data['hora_fim']) && $data['hora_fim']
            ? Carbon::parse($data['hora_fim'])->format('H:i:s')
            : null;

        $data['clinica_id'] = $clinicId;
        $data['tipo'] = $data['tipo'] ?? 'Consulta';
        $data['contato'] = $data['contato'] ?? '';

        $conflict = false;
        if ($data['profissional_id'] && $data['hora_inicio'] && $data['hora_fim']) {
            $conflict = Agendamento::where('profissional_id', $data['profissional_id'])
                ->whereDate('data', $data['data'])
                ->whereNotIn('status', ['cancelado', 'faltou'])
                ->where(function ($q) use ($data) {
                    $q->where('hora_inicio', '<', $data['hora_fim'])
                        ->where('hora_fim', '>', $data['hora_inicio']);
                })
                ->exists();
        }

        if ($conflict) {
            return response()->json([
                'success' => false,
                'message' => 'Existe agendamento ativo nessa faixa de horário.',
            ], 409);
        }

        Agendamento::create($data);
        $cacheKey = "agendamentos_{$clinicId}_" . Carbon::parse($data['data'])->format('Y-m-d');
        Cache::forget($cacheKey);

        return response()->json(['success' => true]);
    }

    public function update(Request $request, Agendamento $agendamento)
    {
        $clinicId = app()->bound('clinic_id') ? app('clinic_id') : null;

        $data = $request->validate([
            'data' => 'required|date',
            'hora_inicio' => 'nullable|required_unless:status,lista_espera',
            'hora_fim' => 'nullable|required_unless:status,lista_espera',
            'observacao' => 'nullable|string',
            'status' => 'required|in:confirmado,pendente,cancelado,faltou,lista_espera',
            'profissional_id' => 'nullable|exists:profissionais,id',
        ]);

        $data['profissional_id'] = $data['profissional_id'] ?? null;
        $data['hora_inicio'] = isset($data['hora_inicio']) && $data['hora_inicio']
            ? Carbon::parse($data['hora_inicio'])->format('H:i:s')
            : null;
        $data['hora_fim'] = isset($data['hora_fim']) && $data['hora_fim']
            ? Carbon::parse($data['hora_fim'])->format('H:i:s')
            : null;

        $conflict = false;
        if ($data['profissional_id'] && $data['hora_inicio'] && $data['hora_fim']) {
            $conflict = Agendamento::where('profissional_id', $data['profissional_id'])
                ->whereDate('data', $data['data'])
                ->where('id', '!=', $agendamento->id)
                ->whereNotIn('status', ['cancelado', 'faltou'])
                ->where(function ($q) use ($data) {
                    $q->where('hora_inicio', '<', $data['hora_fim'])
                        ->where('hora_fim', '>', $data['hora_inicio']);
                })
                ->exists();
        }

        if ($conflict) {
            return response()->json([
                'success' => false,
                'message' => 'Existe agendamento ativo nessa faixa de horário.',
            ], 409);
        }

        $oldDate = $agendamento->data;
        $agendamento->update([
            'data' => $data['data'],
            'hora_inicio' => $data['hora_inicio'],
            'hora_fim' => $data['hora_fim'],
            'observacao' => $data['observacao'] ?? '',
            'status' => $data['status'],
            'profissional_id' => $data['profissional_id'],
        ]);

        if ($clinicId) {
            $newDate = Carbon::parse($data['data'])->format('Y-m-d');
            Cache::forget("agendamentos_{$clinicId}_{$newDate}");

            $oldDateFormatted = Carbon::parse($oldDate)->format('Y-m-d');
            if ($oldDateFormatted !== $newDate) {
                Cache::forget("agendamentos_{$clinicId}_{$oldDateFormatted}");
            }
        }

        return response()->json(['success' => true]);
    }
}
