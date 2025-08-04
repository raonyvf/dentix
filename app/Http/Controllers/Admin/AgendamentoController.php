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
        $professionals = [];
        if ($clinicId) {
            $clinic = Clinic::with(['profissionais.pessoa'])->find($clinicId);
            if ($clinic) {
                $professionals = $clinic->profissionais->map(function ($prof) {
                    $gender = $prof->pessoa->sexo ?? null;
                    $prefix = $gender === 'Masculino' ? 'Dr. ' : ($gender === 'Feminino' ? 'Dra. ' : '');
                    return [
                        'id' => $prof->id,
                        'name' => $prefix . ($prof->pessoa->primeiro_nome ?? ''),
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
          
            $agendamentos = Agendamento::with(['paciente.pessoa'])
                ->where('clinica_id', $clinicId)
                ->whereDate('data', $date)
                ->get();
            $cacheKey = "agendamentos_{$clinicId}_{$date}";
            $agendamentos = Cache::remember($cacheKey, 60, function () use ($clinicId, $date) {
                return Agendamento::with(['paciente.pessoa'])
                    ->where('clinica_id', $clinicId)
                    ->whereDate('data', $date)
                    ->get();
            });

            foreach ($agendamentos as $ag) {
                $pessoa = optional($ag->paciente)->pessoa;
                $agenda[$ag->profissional_id][$ag->hora_inicio] = [
                    'paciente' => $pessoa ? trim(($pessoa->primeiro_nome ?? '') . ' ' . ($pessoa->ultimo_nome ?? '')) : '',
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
            'observacao' => 'nullable|string',
        ]);

        $data['clinica_id'] = $clinicId;
        $data['status'] = 'confirmado';

        Agendamento::create($data);
        $cacheKey = "agendamentos_{$clinicId}_" . Carbon::parse($data['data'])->format('Y-m-d');
        Cache::forget($cacheKey);

        return response()->json(['success' => true]);
    }
}
