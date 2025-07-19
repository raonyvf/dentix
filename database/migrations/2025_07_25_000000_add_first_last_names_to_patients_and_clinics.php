<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('patients', function (Blueprint $table) {
            $table->string('first_name')->nullable()->after('clinic_id');
            $table->string('middle_name')->nullable()->after('first_name');
            $table->string('last_name')->nullable()->after('middle_name');
            $table->string('responsavel_first_name')->nullable()->after('responsavel');
            $table->string('responsavel_middle_name')->nullable()->after('responsavel_first_name');
            $table->string('responsavel_last_name')->nullable()->after('responsavel_middle_name');
        });

        Schema::table('clinics', function (Blueprint $table) {
            $table->string('responsavel_first_name')->nullable()->after('responsavel_tecnico');
            $table->string('responsavel_middle_name')->nullable()->after('responsavel_first_name');
            $table->string('responsavel_last_name')->nullable()->after('responsavel_middle_name');
        });
    }

    public function down(): void
    {
        Schema::table('patients', function (Blueprint $table) {
            $table->dropColumn([
                'first_name',
                'middle_name',
                'last_name',
                'responsavel_first_name',
                'responsavel_middle_name',
                'responsavel_last_name'
            ]);
        });

        Schema::table('clinics', function (Blueprint $table) {
            $table->dropColumn([
                'responsavel_first_name',
                'responsavel_middle_name',
                'responsavel_last_name'
            ]);
        });
    }
};
