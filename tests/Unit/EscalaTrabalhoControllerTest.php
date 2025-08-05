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
}
