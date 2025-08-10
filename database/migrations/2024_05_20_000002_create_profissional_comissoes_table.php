<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('profissional_comissoes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('profissional_id')->constrained('profissionais');
            $table->foreignId('clinica_id')->constrained('clinicas');
            $table->decimal('comissao', 5, 2)->nullable();
            $table->decimal('protese', 5, 2)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('profissional_comissoes');
    }
};
