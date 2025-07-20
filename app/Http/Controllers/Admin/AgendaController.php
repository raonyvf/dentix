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
}
