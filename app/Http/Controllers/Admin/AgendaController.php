<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AgendaController extends Controller
{
    public function index(Request $request)
    {
        $month = $request->query('month');
        $year = $request->query('year');

        if ($month && $year) {
            $date = Carbon::createFromDate($year, $month, 1);
        } else {
            $date = Carbon::now();
        }

        Carbon::setLocale(config('app.locale'));

        $currentDate = $date->copy();
        $daysInMonth = $currentDate->daysInMonth;
        $firstDayOfWeek = $currentDate->copy()->startOfMonth()->dayOfWeekIso; // 1 (Mon) - 7 (Sun)
        $prevDate = $currentDate->copy()->subMonth();
        $nextDate = $currentDate->copy()->addMonth();
        $today = Carbon::today();

        return view('agenda', compact(
            'currentDate',
            'daysInMonth',
            'firstDayOfWeek',
            'prevDate',
            'nextDate',
            'today'
        ));
    }

    public function horarios(Request $request)
    {
        $date = Carbon::parse($request->query('date'));

        $clinicId = app()->bound('clinic_id') ? app('clinic_id') : null;
        if (! $clinicId) {
            return response()->json(['closed' => true]);
        }

        $clinic = \App\Models\Clinic::with('horarios')->find($clinicId);
        if (! $clinic) {
            return response()->json(['closed' => true]);
        }

        $dias = [
            1 => 'segunda',
            2 => 'terca',
            3 => 'quarta',
            4 => 'quinta',
            5 => 'sexta',
            6 => 'sabado',
            0 => 'domingo',
        ];
        $dia = $dias[$date->dayOfWeek];

        $intervalos = $clinic->horarios()
            ->withoutGlobalScope('organization')
            ->where('dia_semana', $dia)
            ->orderBy('hora_inicio')
            ->get();

        if ($intervalos->isEmpty()) {
            return response()->json(['closed' => true]);
        }

        $horarios = [];
        $startTimes = [];
        $endTimes = [];
        $debugIntervals = [];
        foreach ($intervalos as $int) {
            if (!$int->hora_inicio || !$int->hora_fim) {
                continue;
            }
            $start = Carbon::createFromTimeString($int->hora_inicio);
            $end = Carbon::createFromTimeString($int->hora_fim);
            $startTimes[] = $start->format('H:i');
            $endTimes[] = $end->format('H:i');
            $debugIntervals[] = [
                'inicio' => $start->format('H:i'),
                'fim' => $end->format('H:i'),
            ];
            for ($time = $start->copy(); $time <= $end; $time->addMinutes(30)) {
                $horarios[] = $time->format('H:i');
            }
        }

        if (empty($horarios)) {
            return response()->json(['closed' => true]);
        }
        $startTime = min($startTimes);
        $endTime = max($endTimes);

        // Uncomment the line below for raw dump of schedule intervals during debugging
        // dd($debugIntervals);

        return response()->json([
            'closed' => false,
            'horarios' => $horarios,
            'start' => $startTime,
            'end' => $endTime,
            'intervals' => $debugIntervals,
        ]);
    }
}
