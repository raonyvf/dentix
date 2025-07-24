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
        $week = $request->input('week');
        $week = $week ? Carbon::parse($week)->startOfWeek(Carbon::MONDAY) : Carbon::now()->startOfWeek(Carbon::MONDAY);
        $dias = ['segunda','terca','quarta','quinta','sexta','sabado','domingo'];
        $cadeiras = $clinicId ? Cadeira::where('clinic_id', $clinicId)->get() : collect();
        $schedules = EscalaTrabalho::with(['profissional.person'])
            ->where('clinic_id', $clinicId)
            ->where('semana', $week->toDateString())
            ->get()
            ->groupBy(['cadeira_id','dia_semana']);
        $dentistas = Profissional::where('cargo', 'like', '%dent%')
            ->orWhere('cargo', 'like', '%odont%')
            ->get();
        return view('escalas.index', compact('clinics','clinicId','week','dias','cadeiras','schedules','dentistas'));
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

        return redirect()->route('escalas.index', ['clinic_id' => $data['clinic_id'], 'week' => $data['semana']])
            ->with('success', 'Escala salva com sucesso.');
    }
}
