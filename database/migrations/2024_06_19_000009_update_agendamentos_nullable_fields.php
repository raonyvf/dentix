<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('agendamentos', function (Blueprint $table) {
            $table->foreignId('profissional_id')->nullable()->change();
            $table->time('hora_inicio')->nullable()->change();
            $table->time('hora_fim')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('agendamentos', function (Blueprint $table) {
            $table->foreignId('profissional_id')->nullable(false)->change();
            $table->time('hora_inicio')->nullable(false)->change();
            $table->time('hora_fim')->nullable(false)->change();
        });
    }
};
