<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('agendamentos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('clinica_id')->constrained('clinicas');
            $table->foreignId('profissional_id')->constrained('profissionais');
            $table->foreignId('paciente_id')->nullable()->constrained('pacientes');
            $table->string('tipo');
            $table->string('contato')->nullable();
            $table->string('status')->default('pendente');
            $table->date('data');
            $table->time('hora_inicio');
            $table->time('hora_fim');
            $table->text('observacao')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('agendamentos');
    }
};
