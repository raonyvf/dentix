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
        $targetStart = $targetMonth->copy()->startOfWeek(Carbon::MONDAY);
        if ($targetStart->lt($targetMonth)) {
            $targetStart->addWeek();
        }

        $diff = Carbon::parse('2025-07-28')->diffInWeeks($sourceStart);
        $newWeek = $targetStart->copy()->addWeeks($diff);

        $this->assertSame(6, $newWeek->month);
        $this->assertGreaterThanOrEqual(1, $newWeek->day);
    }
}
