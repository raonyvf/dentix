<?php

use PHPUnit\Framework\TestCase;
use Carbon\Carbon;

class EscalaTrabalhoControllerTest extends TestCase
{
    private function overlaps($existingStart, $existingEnd, $newStart, $newEnd)
    {
        return Carbon::parse($existingStart) < Carbon::parse($newEnd)
            && Carbon::parse($existingEnd) > Carbon::parse($newStart);
    }

    private function withinClinicHours($clinicStart, $clinicEnd, $start, $end)
    {
        return !(
            Carbon::parse($start) < Carbon::parse($clinicStart)
            || Carbon::parse($end) > Carbon::parse($clinicEnd)
        );
    }

    public function test_allows_adjacent_times()
    {
        $this->assertFalse(
            $this->overlaps('08:00', '09:00', '09:00', '10:00')
        );
    }

    public function test_detects_overlapping_times()
    {
        $this->assertTrue(
            $this->overlaps('08:00', '10:00', '09:00', '11:00')
        );
    }

    public function test_detects_schedule_outside_clinic_hours()
    {
        $this->assertFalse(
            $this->withinClinicHours('08:00', '17:00', '07:00', '09:00')
        );
    }

    public function test_allows_schedule_within_clinic_hours()
    {
        $this->assertTrue(
            $this->withinClinicHours('08:00', '17:00', '09:00', '10:00')
        );
    }

    public function test_accepts_past_year_and_month()
    {
        $year = 1999;
        $monthNumber = 11;
        $selected = Carbon::create($year, $monthNumber, 1)->startOfMonth();
        $this->assertSame('1999-11-01', $selected->toDateString());

        $meses = collect(range(1, 12))->map(fn($m) => Carbon::create($year, $m, 1)->startOfMonth());
        $this->assertCount(12, $meses);
        $this->assertTrue($meses->first()->isSameMonth(Carbon::create($year, 1, 1)));
        $this->assertTrue($meses->last()->isSameMonth(Carbon::create($year, 12, 1)));
    }

    public function test_accepts_future_year_and_month()
    {
        $year = Carbon::now()->year + 5;
        $monthNumber = 3;
        $selected = Carbon::create($year, $monthNumber, 1)->startOfMonth();
        $this->assertSame($year.'-03-01', $selected->toDateString());

        $meses = collect(range(1, 12))->map(fn($m) => Carbon::create($year, $m, 1)->startOfMonth());
        $this->assertTrue($meses->contains(fn($m) => $m->isSameMonth($selected)));
    }

    public function test_recurring_creation_generates_expected_records()
    {
        $start = Carbon::parse('2024-01-01'); // Monday
        $repeatWeeks = 3;
        $dias = ['segunda', 'quarta'];
        $created = [];
        for ($week = $start->copy(), $i = 0; $i < $repeatWeeks; $i++, $week->addWeek()) {
            foreach ($dias as $dia) {
                $created[] = [$week->toDateString(), $dia];
            }
        }
        $this->assertCount(6, $created);
        $this->assertSame('2024-01-01', $created[0][0]);
        $this->assertSame('quarta', $created[1][1]);
    }

    private function conflictDetected($start, $dias, $repeatWeeks, $existing)
    {
        for ($week = $start->copy(), $i = 0; $i < $repeatWeeks; $i++, $week->addWeek()) {
            $weekStart = $week->toDateString();
            foreach ($dias as $diaNome) {
                $dia = $diaNome;
                foreach ($existing as $e) {
                    if ($e['semana'] === $weekStart && $e['dia'] === $dia
                        && $this->overlaps($e['hora_inicio'], $e['hora_fim'], '08:00', '09:00')
                        && ($e['cadeira'] === 1 || $e['profissional'] === 1)) {
                        return true;
                    }
                }
            }
        }
        return false;
    }

    public function test_blocks_conflicts_in_future_weeks()
    {
        $start = Carbon::parse('2024-01-01');
        $dias = ['segunda'];
        $existingChair = [
            ['semana' => '2024-01-08', 'dia' => 'segunda', 'hora_inicio' => '08:00', 'hora_fim' => '10:00', 'cadeira' => 1, 'profissional' => 2],
        ];
        $existingProf = [
            ['semana' => '2024-01-08', 'dia' => 'segunda', 'hora_inicio' => '08:00', 'hora_fim' => '10:00', 'cadeira' => 2, 'profissional' => 1],
        ];

        $this->assertTrue($this->conflictDetected($start, $dias, 3, $existingChair));
        $this->assertTrue($this->conflictDetected($start, $dias, 3, $existingProf));
    }
}
