<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('agendamentos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('clinic_id')->constrained('clinics');
            $table->foreignId('profissional_id')->constrained('profissionais');
            $table->string('paciente');
            $table->string('tipo')->nullable();
            $table->string('contato')->nullable();
            $table->enum('status', ['confirmado', 'cancelado', 'vago'])->default('confirmado');
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
