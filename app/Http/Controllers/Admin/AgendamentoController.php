<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Agendamento;
use App\Models\Clinic;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AgendamentoController extends Controller
{
    public function index(Request $request)
    {
        $clinicId = app()->bound('clinic_id') ? app('clinic_id') : null;
        $professionals = [];
        if ($clinicId) {
            $clinic = Clinic::with(['profissionais.person'])->find($clinicId);
            if ($clinic) {
                $professionals = $clinic->profissionais->map(function ($prof) {
                    $gender = $prof->person->sexo ?? null;
                    $prefix = $gender === 'Masculino' ? 'Dr. ' : ($gender === 'Feminino' ? 'Dra. ' : '');
                    return [
                        'id' => $prof->id,
                        'name' => $prefix . ($prof->person->first_name ?? ''),
                    ];
                })->toArray();
            }
        }

        $horarios = [];
        $startTime = Carbon::createFromTime(0, 0);
        $endTime = Carbon::createFromTime(23, 30);
        for ($time = $startTime->copy(); $time <= $endTime; $time->addMinutes(30)) {
            $horarios[] = $time->format('H:i');
        }

        $date = $request->query('date', Carbon::today()->format('Y-m-d'));
        $agenda = [];
        if ($clinicId) {
            $agendamentos = Agendamento::with(['patient.person'])
                ->where('clinic_id', $clinicId)
                ->whereDate('data', $date)
                ->get();
            foreach ($agendamentos as $ag) {
                $person = optional($ag->patient)->person;
                $agenda[$ag->profissional_id][$ag->hora_inicio] = [
                    'paciente' => $person ? trim(($person->first_name ?? '') . ' ' . ($person->last_name ?? '')) : '',
                    'tipo' => $ag->tipo ?? '',
                    'contato' => $ag->contato ?? '',
                    'status' => $ag->status ?? 'confirmado',
                ];
            }
        }

        return view('agendamentos.index', compact('professionals', 'horarios', 'agenda'));
    }

    public function store(Request $request)
    {
        $clinicId = app()->bound('clinic_id') ? app('clinic_id') : null;
        if (! $clinicId) {
            return response()->json(['success' => false], 400);
        }

        $data = $request->validate([
            'profissional_id' => 'required|exists:profissionais,id',
            'patient_id' => 'required|exists:patients,id',
            'data' => 'required|date',
            'hora_inicio' => 'required',
            'hora_fim' => 'required',
            'observacao' => 'nullable|string',
        ]);

        $data['clinic_id'] = $clinicId;
        $data['status'] = 'confirmado';

        Agendamento::create($data);

        return response()->json(['success' => true]);
    }
}
