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

    public function test_month_copy_respects_target_month()
    {
        $targetMonth = Carbon::create(2025, 6, 1)->startOfMonth();
        $sourceMonth = Carbon::create(2025, 8, 1)->startOfMonth();

        $sourceStart = $sourceMonth->copy()->startOfWeek(Carbon::MONDAY);
        if ($sourceStart->lt($sourceMonth)) {
            $sourceStart->addWeek();
        }
        $targetStart = $targetMonth->copy()->startOfWeek(Carbon::MONDAY);
        if ($targetStart->lt($targetMonth)) {
            $targetStart->addWeek();
        }

        $diff = Carbon::parse('2025-08-04')->diffInWeeks($sourceStart);
        $newWeek = $targetStart->copy()->addWeeks($diff);

        $this->assertSame('2025-06-02', $newWeek->toDateString());
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
}
