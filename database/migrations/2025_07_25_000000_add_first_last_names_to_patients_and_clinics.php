<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('clinics', function (Blueprint $table) {
            $table->string('responsavel_first_name')->nullable()->after('responsavel_tecnico');
            $table->string('responsavel_middle_name')->nullable()->after('responsavel_first_name');
            $table->string('responsavel_last_name')->nullable()->after('responsavel_middle_name');
        });
    }

    public function down(): void
    {
        Schema::table('clinics', function (Blueprint $table) {
            $table->dropColumn([
                'responsavel_first_name',
                'responsavel_middle_name',
                'responsavel_last_name'
            ]);
        });
    }
};
