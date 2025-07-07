<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('cadeiras', function (Blueprint $table) {
            $table->id();
            $table->foreignId('unidade_id')->constrained('unidades');
            $table->string('nome');
            $table->string('especialidade')->nullable();
            $table->string('status')->nullable();
            $table->text('horarios_disponiveis')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cadeiras');
    }
};
