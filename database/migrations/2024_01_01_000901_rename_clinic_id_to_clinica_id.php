<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        $tables = [
            'horarios',
            'clinica_profissional',
            'escalas_trabalho',
            'profissional_horarios',
        ];

        foreach ($tables as $tableName) {
            if (Schema::hasColumn($tableName, 'clinic_id')) {
                Schema::table($tableName, function (Blueprint $table) {
                    $table->renameColumn('clinic_id', 'clinica_id');
                });
            }
        }
    }

    public function down(): void
    {
        $tables = [
            'horarios',
            'clinica_profissional',
            'escalas_trabalho',
            'profissional_horarios',
        ];

        foreach ($tables as $tableName) {
            if (Schema::hasColumn($tableName, 'clinica_id')) {
                Schema::table($tableName, function (Blueprint $table) {
                    $table->renameColumn('clinica_id', 'clinic_id');
                });
            }
        }
    }
};
