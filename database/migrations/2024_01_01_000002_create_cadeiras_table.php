<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cadeiras', function (Blueprint $table) {
            $table->id();
            $table->foreignId('unidade_id')->constrained('unidades');
            $table->string('nome');
            $table->string('especialidade');
            $table->string('status');
            $table->string('horarios_disponiveis');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cadeiras');
    }
};
