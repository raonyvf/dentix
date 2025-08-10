<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('escalas_trabalho', function (Blueprint $table) {
            $table->id();
            $table->foreignId('organizacao_id')->constrained('organizacoes');
            $table->foreignId('clinica_id')->constrained('clinicas');
            $table->foreignId('cadeira_id')->constrained('cadeiras');
            $table->foreignId('profissional_id')->constrained('profissionais');
            $table->date('semana');
            $table->unsignedTinyInteger('dia_semana');
            $table->time('hora_inicio');
            $table->time('hora_fim');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('escalas_trabalho');
    }
};
