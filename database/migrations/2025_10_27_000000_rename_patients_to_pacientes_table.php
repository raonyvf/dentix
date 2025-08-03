<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::rename('patients', 'pacientes');
    }

    public function down(): void
    {
        Schema::rename('pacientes', 'patients');
    }
};
