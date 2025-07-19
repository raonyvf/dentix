<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('respostas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('paciente_id')->constrained('patients')->cascadeOnDelete();
            $table->foreignId('formulario_id')->constrained('formularios')->cascadeOnDelete();
            $table->foreignId('pergunta_id')->constrained('perguntas')->cascadeOnDelete();
            $table->text('resposta')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('respostas');
    }
};
