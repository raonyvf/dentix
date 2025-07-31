<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\EscalaTrabalho;
use App\Models\Clinic;
use App\Models\Cadeira;
use App\Models\Profissional;
use Carbon\Carbon;

class EscalaTrabalhoController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $clinics = $user->isOrganizationAdmin() ? Clinic::all() : $user->clinics()->get();
        $clinicId = $request->input('clinic_id', $clinics->first()->id ?? null);
        $view = $request->input('view', 'week');

        $dias = ['segunda','terca','quarta','quinta','sexta','sabado','domingo'];
        $cadeiras = $clinicId ? Cadeira::where('clinic_id', $clinicId)->get() : collect();
        $dentistas = Profissional::where(function($q){
                $q->where('funcao', 'Dentista')->orWhere('cargo', 'Dentista');
            })
            ->orWhereHas('user', function($q){
                $q->whereNotNull('especialidade');
            })
            ->with(['person'])
            ->get();

        if ($view === 'month') {
            $month = $request->input('month');
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
                ->where('clinic_id', $clinicId)
                ->whereBetween('semana', [$weeks->first()->toDateString(), $weeks->last()->toDateString()])
                ->get()
                ->groupBy(['semana','cadeira_id','dia_semana']);

            return view('escalas.index', compact('clinics','clinicId','view','month','weeks','dias','cadeiras','escalas','dentistas','mesesDisponiveis'));
        }

        $week = $request->input('week');
        $week = $week ? Carbon::parse($week)->startOfWeek(Carbon::MONDAY) : Carbon::now()->startOfWeek(Carbon::MONDAY);
        $semanasDisponiveis = collect(range(-2, 5))->map(fn($i) => Carbon::now()->startOfWeek(Carbon::MONDAY)->addWeeks($i));
        $escalas = EscalaTrabalho::with(['profissional.person','profissional.user'])
            ->where('clinic_id', $clinicId)
            ->where('semana', $week->toDateString())
            ->get()
            ->groupBy(['cadeira_id','dia_semana']);

        return view('escalas.index', compact('clinics','clinicId','view','week','dias','cadeiras','escalas','dentistas','semanasDisponiveis'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'clinic_id' => 'required|exists:clinics,id',
            'cadeira_id' => 'required|exists:cadeiras,id',
            'profissional_id' => 'required|exists:profissionais,id',
            'semana' => 'required|date',
            'dias' => 'required|array',
            'dias.*' => 'in:segunda,terca,quarta,quinta,sexta,sabado,domingo',
            'hora_inicio' => 'required',
            'hora_fim' => 'required',
        ]);

        foreach ($data['dias'] as $dia) {
            // conflict: same chair and time
            $conflict = EscalaTrabalho::where('cadeira_id', $data['cadeira_id'])
                ->where('semana', $data['semana'])
                ->where('dia_semana', $dia)
                ->where(function($q) use ($data) {
                    $q->whereBetween('hora_inicio', [$data['hora_inicio'], $data['hora_fim']])
                      ->orWhereBetween('hora_fim', [$data['hora_inicio'], $data['hora_fim']]);
                })->exists();

            $conflictProf = EscalaTrabalho::where('profissional_id', $data['profissional_id'])
                ->where('semana', $data['semana'])
                ->where('dia_semana', $dia)
                ->where(function($q) use ($data) {
                    $q->whereBetween('hora_inicio', [$data['hora_inicio'], $data['hora_fim']])
                      ->orWhereBetween('hora_fim', [$data['hora_inicio'], $data['hora_fim']]);
                })->exists();

            if ($conflict || $conflictProf) {
                return back()->with('error', 'Conflito de horários detectado.');
            }

            EscalaTrabalho::create([
                'clinic_id' => $data['clinic_id'],
                'cadeira_id' => $data['cadeira_id'],
                'profissional_id' => $data['profissional_id'],
                'semana' => $data['semana'],
                'dia_semana' => $dia,
                'hora_inicio' => $data['hora_inicio'],
                'hora_fim' => $data['hora_fim'],
            ]);
        }

        $params = ['clinic_id' => $data['clinic_id'], 'week' => $data['semana']];
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
