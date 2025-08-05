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
        $clinics = $user->isOrganizationAdmin() ? Clinic::all() : $user->clinics()->get();
        $clinicId = $request->input('clinic_id', $clinics->first()->id ?? null);
        $view = $request->input('view', 'week');

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

        if ($view === 'month') {
            $month = $request->input('month');
            if (! $month && $request->filled('week')) {
                $month = Carbon::parse($request->input('week'))->startOfMonth()->format('Y-m');
            }
            $month = $month ? Carbon::parse($month)->startOfMonth() : Carbon::now()->startOfMonth();
            $mesesDisponiveis = collect(range(-2, 2))->map(fn($i) => Carbon::now()->startOfMonth()->addMonths($i));

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

            return view('escalas.index', compact('clinics','clinicId','view','month','weeks','dias','cadeiras','escalas','dentistas','mesesDisponiveis'));
        }

        $week = $request->input('week');
        $week = $week ? Carbon::parse($week)->startOfWeek(Carbon::MONDAY) : Carbon::now()->startOfWeek(Carbon::MONDAY);
        $semanasDisponiveis = collect(range(-2, 5))->map(fn($i) => Carbon::now()->startOfWeek(Carbon::MONDAY)->addWeeks($i));
        $escalas = EscalaTrabalho::with(['profissional.person','profissional.user'])
            ->where('clinica_id', $clinicId)
            ->whereBetween('semana', [
                $week->toDateString(),
                $week->copy()->addDays(6)->toDateString(),
            ])
            ->orderBy('hora_inicio')
            ->get()
            ->groupBy(['cadeira_id','dia_semana'], preserveKeys: true);

        return view('escalas.index', compact('clinics','clinicId','view','week','dias','cadeiras','escalas','dentistas','semanasDisponiveis'));
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

                $conflict = EscalaTrabalho::where('clinica_id', $data['clinic_id'])
                    ->where('cadeira_id', $data['cadeira_id'])
                    ->where('semana', $data['semana'])
                    ->where('dia_semana', $dia)
                    ->where(function ($q) use ($data) {
                        $q->where('hora_inicio', '<', $data['hora_fim'])
                          ->where('hora_fim', '>', $data['hora_inicio']);
                    })->exists();

                $conflictProf = EscalaTrabalho::where('clinica_id', $data['clinic_id'])
                    ->where('profissional_id', $data['profissional_id'])
                    ->where('semana', $data['semana'])
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
                    'semana' => $data['semana'],
                    'dia_semana' => $dia,
                    'hora_inicio' => $data['hora_inicio'],
                    'hora_fim' => $data['hora_fim'],
                ]);
            }
        }

        $params = ['clinic_id' => $data['clinic_id']];
        if ($request->filled('semana')) {
            $params['week'] = $request->input('semana');
        }
        if ($request->filled('view')) {
            $params['view'] = $request->input('view');
        }
        if ($request->filled('month')) {
            $params['month'] = $request->input('month');
        }

        return redirect()->route('escalas.index', $params)
            ->with('success', 'Escala salva com sucesso.');
    }
}
