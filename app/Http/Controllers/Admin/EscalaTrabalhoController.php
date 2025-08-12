<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\EscalaTrabalho;
use App\Models\Clinic;
use App\Models\Cadeira;
use App\Models\Profissional;
use Carbon\Carbon;
use Illuminate\Validation\Rule;
use App\Enums\DiaSemana;

class EscalaTrabalhoController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $clinics = $user->isOrganizationAdmin()
            ? Clinic::with('horarios')->get()
            : $user->clinics()->with('horarios')->get();
        $clinicId = $request->input('clinic_id', $clinics->first()->id ?? null);

        $dias = DiaSemana::cases();
        $cadeiras = $clinicId ? Cadeira::where('clinica_id', $clinicId)->get() : collect();

        $dentistas = Profissional::when($clinicId, function ($query) use ($clinicId) {
                $query->whereHas('clinics', fn($q) => $q->where('clinicas.id', $clinicId));
            })
            ->where(function($q){
                $q->where('funcao', 'Dentista')
                  ->orWhere('cargo', 'Dentista')
                  ->orWhereHas('user', fn($u) => $u->whereNotNull('especialidade'));
            })
            ->with('person')
            ->get();

        $yearParam = $request->input('year');
        $monthParam = $request->input('month');

        $year = $yearParam ? (int)$yearParam : Carbon::now()->year;
        $monthNumber = $monthParam ? (int)$monthParam : Carbon::now()->month;

        $month = Carbon::create($year, $monthNumber, 1)->startOfMonth();
        $mesesDisponiveis = collect(range(1, 12))->map(fn($m) => Carbon::create($year, $m, 1)->startOfMonth());

        $weeks = collect();
        $current = $month->copy()->startOfWeek(Carbon::MONDAY);
        $last = $month->copy()->endOfMonth()->endOfWeek(Carbon::SUNDAY);
        while ($current->lte($last)) {
            $weeks->push($current->copy());
            $current->addWeek();
        }

        $escalas = EscalaTrabalho::with(['profissional.person','profissional.user'])
            ->where('clinica_id', $clinicId)
            ->whereBetween('semana', [$weeks->first()->toDateString(), $weeks->last()->toDateString()])
            ->orderBy('hora_inicio')
            ->get()
            ->groupBy([fn($e) => $e->semana->toDateString(), 'cadeira_id', 'dia_semana'], preserveKeys: true);

        return view('escalas.index', compact('clinics','clinicId','month','weeks','dias','cadeiras','escalas','dentistas','mesesDisponiveis'));
    }

    public function store(Request $request)
    {
        if ($request->filled('datas')) {
            $data = $request->validate([
                'clinic_id' => 'required|exists:clinicas,id',
                'cadeira_id' => 'required|exists:cadeiras,id',
                'profissional_id' => [
                    'required',
                    'exists:profissionais,id',
                    Rule::exists('clinica_profissional', 'profissional_id')->where(fn($q) => $q->where('clinica_id', $request->input('clinic_id'))),
                ],
                'datas' => 'required|array',
                'datas.*' => 'date',
                'hora_inicio' => 'required',
                'hora_fim' => 'required',
            ]);
        } else {
            $data = $request->validate([
                'clinic_id' => 'required|exists:clinicas,id',
                'cadeira_id' => 'required|exists:cadeiras,id',
                'profissional_id' => [
                    'required',
                    'exists:profissionais,id',
                    Rule::exists('clinica_profissional', 'profissional_id')->where(fn($q) => $q->where('clinica_id', $request->input('clinic_id'))),
                ],
                'semana' => 'required|date',
                'dias' => 'required|array',
                'dias.*' => 'in:segunda,terca,quarta,quinta,sexta,sabado,domingo',
                'hora_inicio' => 'required',
                'hora_fim' => 'required',
                'repeat_until' => 'nullable|date|after_or_equal:semana',
                'repeat_weeks' => 'nullable|integer|min:1',
            ]);
        }

        $clinic = Clinic::with('horarios')->find($data['clinic_id']);

        if(isset($data['datas'])) {
            foreach ($data['datas'] as $d) {
                $date = Carbon::parse($d);
                $weekStart = $date->copy()->startOfWeek(Carbon::MONDAY)->toDateString();
                $dia = $date->isoWeekday();

                $ref = $clinic->horarios->firstWhere('dia_semana', $dia);
                if (! $ref
                    || Carbon::parse($data['hora_inicio']) < Carbon::parse($ref->hora_inicio)
                    || Carbon::parse($data['hora_fim']) > Carbon::parse($ref->hora_fim)) {
                    return back()->with('error', 'Horário fora do expediente da clínica.');
                }

                $conflict = EscalaTrabalho::where('clinica_id', $data['clinic_id'])
                    ->where('cadeira_id', $data['cadeira_id'])
                    ->where('semana', $weekStart)
                    ->where('dia_semana', $dia)
                    ->where(function ($q) use ($data) {
                        $q->where('hora_inicio', '<', $data['hora_fim'])
                          ->where('hora_fim', '>', $data['hora_inicio']);
                    })->exists();

                $conflictProf = EscalaTrabalho::where('clinica_id', $data['clinic_id'])
                    ->where('profissional_id', $data['profissional_id'])
                    ->where('semana', $weekStart)
                    ->where('dia_semana', $dia)
                    ->where(function ($q) use ($data) {
                        $q->where('hora_inicio', '<', $data['hora_fim'])
                          ->where('hora_fim', '>', $data['hora_inicio']);
                    })->exists();

                if ($conflict || $conflictProf) {
                    return back()->with('error', 'Conflito de horários detectado.');
                }

                EscalaTrabalho::create([
                    'clinica_id' => $data['clinic_id'],
                    'cadeira_id' => $data['cadeira_id'],
                    'profissional_id' => $data['profissional_id'],
                    'semana' => $weekStart,
                    'dia_semana' => $dia,
                    'hora_inicio' => $data['hora_inicio'],
                    'hora_fim' => $data['hora_fim'],
                ]);
            }
        } else {
            $startWeek = Carbon::parse($data['semana'])->startOfWeek(Carbon::MONDAY);
            $endWeek = $startWeek->copy();
            if (!empty($data['repeat_until'])) {
                $endWeek = Carbon::parse($data['repeat_until'])->startOfWeek(Carbon::MONDAY);
            } elseif (!empty($data['repeat_weeks'])) {
                $endWeek = $startWeek->copy()->addWeeks($data['repeat_weeks'] - 1);
            }

            for ($week = $startWeek->copy(); $week->lte($endWeek); $week->addWeek()) {
                foreach ($data['dias'] as $diaNome) {
                    $dia = DiaSemana::fromName($diaNome)?->value;
                    if (! $dia) {
                        continue;
                    }

                    $ref = $clinic->horarios->firstWhere('dia_semana', $dia);
                    if (! $ref
                        || Carbon::parse($data['hora_inicio']) < Carbon::parse($ref->hora_inicio)
                        || Carbon::parse($data['hora_fim']) > Carbon::parse($ref->hora_fim)) {
                        return back()->with('error', 'Horário fora do expediente da clínica.');
                    }

                    $weekStart = $week->toDateString();

                    $conflict = EscalaTrabalho::where('clinica_id', $data['clinic_id'])
                        ->where('cadeira_id', $data['cadeira_id'])
                        ->where('semana', $weekStart)
                        ->where('dia_semana', $dia)
                        ->where(function ($q) use ($data) {
                            $q->where('hora_inicio', '<', $data['hora_fim'])
                              ->where('hora_fim', '>', $data['hora_inicio']);
                        })->exists();

                    $conflictProf = EscalaTrabalho::where('clinica_id', $data['clinic_id'])
                        ->where('profissional_id', $data['profissional_id'])
                        ->where('semana', $weekStart)
                        ->where('dia_semana', $dia)
                        ->where(function ($q) use ($data) {
                            $q->where('hora_inicio', '<', $data['hora_fim'])
                              ->where('hora_fim', '>', $data['hora_inicio']);
                        })->exists();

                    if ($conflict || $conflictProf) {
                        return back()->with('error', 'Conflito de horários detectado.');
                    }

                    EscalaTrabalho::create([
                        'clinica_id' => $data['clinic_id'],
                        'cadeira_id' => $data['cadeira_id'],
                        'profissional_id' => $data['profissional_id'],
                        'semana' => $weekStart,
                        'dia_semana' => $dia,
                        'hora_inicio' => $data['hora_inicio'],
                        'hora_fim' => $data['hora_fim'],
                    ]);
                }
            }
        }

        $params = [
            'clinic_id' => $data['clinic_id'],
        ];
        if ($request->filled('year')) {
            $params['year'] = $request->input('year');
        }
        if ($request->filled('month')) {
            $params['month'] = $request->input('month');
        }

        return redirect()->route('escalas.index', $params)
            ->with('success', 'Escala salva com sucesso.');
    }

    public function update(Request $request, EscalaTrabalho $escala)
    {
        $data = $request->validate([
            'profissional_id' => [
                'required',
                'exists:profissionais,id',
                Rule::exists('clinica_profissional', 'profissional_id')
                    ->where(fn($q) => $q->where('clinica_id', $escala->clinica_id)),
            ],
            'cadeira_id' => 'required|exists:cadeiras,id',
            'data' => 'required|date',
            'hora_inicio' => 'required',
            'hora_fim' => 'required',
        ]);

        $date = Carbon::parse($data['data']);
        $weekStart = $date->copy()->startOfWeek(Carbon::MONDAY)->toDateString();
        $dia = $date->isoWeekday();

        $clinic = Clinic::with('horarios')->find($escala->clinica_id);
        $ref = $clinic->horarios->firstWhere('dia_semana', $dia);
        if (! $ref
            || Carbon::parse($data['hora_inicio']) < Carbon::parse($ref->hora_inicio)
            || Carbon::parse($data['hora_fim']) > Carbon::parse($ref->hora_fim)) {
            return back()->with('error', 'Horário fora do expediente da clínica.');
        }

        $conflict = EscalaTrabalho::where('clinica_id', $escala->clinica_id)
            ->where('cadeira_id', $data['cadeira_id'])
            ->where('semana', $weekStart)
            ->where('dia_semana', $dia)
            ->where('id', '!=', $escala->id)
            ->where(function ($q) use ($data) {
                $q->where('hora_inicio', '<', $data['hora_fim'])
                  ->where('hora_fim', '>', $data['hora_inicio']);
            })->exists();

        $conflictProf = EscalaTrabalho::where('clinica_id', $escala->clinica_id)
            ->where('profissional_id', $data['profissional_id'])
            ->where('semana', $weekStart)
            ->where('dia_semana', $dia)
            ->where('id', '!=', $escala->id)
            ->where(function ($q) use ($data) {
                $q->where('hora_inicio', '<', $data['hora_fim'])
                  ->where('hora_fim', '>', $data['hora_inicio']);
            })->exists();

        if ($conflict || $conflictProf) {
            return back()->with('error', 'Conflito de horários detectado.');
        }

        $escala->update([
            'profissional_id' => $data['profissional_id'],
            'cadeira_id' => $data['cadeira_id'],
            'semana' => $weekStart,
            'dia_semana' => $dia,
            'hora_inicio' => $data['hora_inicio'],
            'hora_fim' => $data['hora_fim'],
        ]);

        $params = [
            'clinic_id' => $escala->clinica_id,
        ];
        if ($request->filled('year') && $request->filled('month')) {
            $params['year'] = $request->input('year');
            $params['month'] = $request->input('month');
        } else {
            $date = Carbon::parse($data['data']);
            $params['year'] = $date->year;
            $params['month'] = $date->month;
        }

        return redirect()->route('escalas.index', $params)
            ->with('success', 'Escala atualizada com sucesso.');
    }

    public function destroy(Request $request, EscalaTrabalho $escala)
    {
        $escala->delete();

        $params = [
            'clinic_id' => $escala->clinica_id,
        ];
        if ($request->filled('year')) {
            $params['year'] = $request->input('year');
        }
        if ($request->filled('month')) {
            $params['month'] = $request->input('month');
        }

        return redirect()->route('escalas.index', $params)
            ->with('success', 'Escala excluída com sucesso.');
    }

}
