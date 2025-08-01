<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('clinics', function (Blueprint $table) {
            if (Schema::hasColumn('clinics', 'responsavel_tecnico')) {
                $table->dropColumn('responsavel_tecnico');
            }
        });
    }

    public function down(): void
    {
        Schema::table('clinics', function (Blueprint $table) {
            if (!Schema::hasColumn('clinics', 'responsavel_tecnico')) {
                $table->string('responsavel_tecnico')->nullable()->after('cnpj');
            }
        });
    }
};
