<?php

use PHPUnit\Framework\TestCase;
use Carbon\Carbon;

class AgendaControllerTest extends TestCase
{
    public function test_date_parsing_with_explicit_timezone()
    {
        $original = date_default_timezone_get();
        date_default_timezone_set('Asia/Tokyo');

        $date = Carbon::createFromFormat('Y-m-d', '2024-05-19', config('app.timezone'));

        $this->assertSame(7, $date->dayOfWeekIso);

        date_default_timezone_set($original);
    }
}
