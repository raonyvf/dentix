<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('horarios_profissional', function (Blueprint $table) {
            $table->id();
            $table->foreignId('clinica_id')->constrained('clinics');
            $table->foreignId('profissional_id')->constrained('users');
            $table->enum('dia_semana', ['segunda', 'terca', 'quarta', 'quinta', 'sexta', 'sabado', 'domingo']);
            $table->time('hora_inicio');
            $table->time('hora_fim');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('horarios_profissional');
    }
};
