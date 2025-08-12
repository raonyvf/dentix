<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('perguntas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('formulario_id')->constrained('formularios')->cascadeOnDelete();
            $table->string('enunciado');
            $table->string('tipo');
            $table->text('opcoes')->nullable();
            $table->unsignedInteger('ordem');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('perguntas');
    }
};
