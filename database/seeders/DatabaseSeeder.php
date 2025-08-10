<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Database\Seeders\PatientProfileSeeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            AdminUserSeeder::class,
            PatientProfileSeeder::class,
        ]);
    }
}
