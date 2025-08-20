<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Agendamento;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class AgendamentoController extends Controller
{
    public function index(Request $request)
    {
        $clinicId = app()->bound('clinic_id') ? app('clinic_id') : null;
        $date = $request->query('date', Carbon::today()->format('Y-m-d'));
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

            $cacheKey = "agendamentos_{$clinicId}_{$date}";
            $agendamentos = Cache::remember(
                $cacheKey,
                60,
                function () use ($clinicId, $date, $profIds) {
                    return Agendamento::with(['paciente.pessoa'])
                        ->where('clinica_id', $clinicId)
                        ->whereDate('data', $date)
                        ->whereIn('profissional_id', $profIds)
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

        $professionals = $this->professionalsForDate($clinicId, $date);
        $agenda = [];

        if ($professionals) {
            $profIds = array_column($professionals, 'id');

            $cacheKey = "agendamentos_{$clinicId}_{$date}";
            $agendamentos = Cache::remember(
                $cacheKey,
                60,
                function () use ($clinicId, $date, $profIds) {
                    return Agendamento::with(['paciente.pessoa'])
                        ->where('clinica_id', $clinicId)
                        ->whereDate('data', $date)
                        ->whereIn('profissional_id', $profIds)
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
            'profissional_id' => 'required|exists:profissionais,id',
            'paciente_id' => 'required|exists:pacientes,id',
            'data' => 'required|date',
            'hora_inicio' => 'required',
            'hora_fim' => 'required',
            'tipo' => 'nullable|string',
            'contato' => 'nullable|string',
            'observacao' => 'nullable|string',
            'status' => 'required|in:confirmado,pendente,cancelado,faltou,lista_espera',
        ]);

        $data['hora_inicio'] = Carbon::parse($data['hora_inicio'])->format('H:i:s');
        $data['hora_fim'] = Carbon::parse($data['hora_fim'])->format('H:i:s');

        $data['clinica_id'] = $clinicId;
        $data['tipo'] = $data['tipo'] ?? 'Consulta';
        $data['contato'] = $data['contato'] ?? '';

        $conflict = Agendamento::where('profissional_id', $data['profissional_id'])
            ->whereDate('data', $data['data'])
            ->whereNotIn('status', ['cancelado', 'faltou'])
            ->where(function ($q) use ($data) {
                $q->where('hora_inicio', '<', $data['hora_fim'])
                    ->where('hora_fim', '>', $data['hora_inicio']);
            })
            ->exists();

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
            'hora_inicio' => 'required',
            'hora_fim' => 'required',
            'observacao' => 'nullable|string',
            'status' => 'required|in:confirmado,pendente,cancelado,faltou,lista_espera',
            'profissional_id' => 'required|exists:profissionais,id',
        ]);

        $data['hora_inicio'] = Carbon::parse($data['hora_inicio'])->format('H:i:s');
        $data['hora_fim'] = Carbon::parse($data['hora_fim'])->format('H:i:s');

        $conflict = Agendamento::where('profissional_id', $data['profissional_id'])
            ->whereDate('data', $data['data'])
            ->where('id', '!=', $agendamento->id)
            ->whereNotIn('status', ['cancelado', 'faltou'])
            ->where(function ($q) use ($data) {
                $q->where('hora_inicio', '<', $data['hora_fim'])
                    ->where('hora_fim', '>', $data['hora_inicio']);
            })
            ->exists();

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
