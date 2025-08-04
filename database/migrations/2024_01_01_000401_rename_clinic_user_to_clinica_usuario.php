<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (Schema::hasTable('clinic_user')) {
            Schema::rename('clinic_user', 'clinica_usuario');
        }

        if (Schema::hasColumn('clinica_usuario', 'clinic_id')) {
            Schema::table('clinica_usuario', function (Blueprint $table) {
                $table->renameColumn('clinic_id', 'clinica_id');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('clinica_usuario', 'clinica_id')) {
            Schema::table('clinica_usuario', function (Blueprint $table) {
                $table->renameColumn('clinica_id', 'clinic_id');
            });
        }

        if (Schema::hasTable('clinica_usuario')) {
            Schema::rename('clinica_usuario', 'clinic_user');
        }
    }
};
