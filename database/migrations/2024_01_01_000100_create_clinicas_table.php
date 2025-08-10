<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('clinicas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('organizacao_id')->constrained('organizacoes');
            $table->string('nome');
            $table->string('cnpj')->nullable();
            $table->string('responsavel_first_name')->nullable();
            $table->string('responsavel_middle_name')->nullable();
            $table->string('responsavel_last_name')->nullable();
            $table->string('cro')->nullable();
            $table->string('cro_uf', 2)->nullable();
            $table->string('logradouro')->nullable();
            $table->string('numero')->nullable();
            $table->string('complemento')->nullable();
            $table->string('bairro')->nullable();
            $table->string('cidade')->nullable();
            $table->string('estado')->nullable();
            $table->string('cep')->nullable();
            $table->string('telefone')->nullable();
            $table->string('email')->nullable();
            $table->string('timezone')->nullable();
            $table->timestamps();
        });

        Schema::create('cadeiras', function (Blueprint $table) {
            $table->id();
            $table->foreignId('organizacao_id')->constrained('organizacoes');
            $table->foreignId('clinica_id')->constrained('clinicas');
            $table->string('nome');
            $table->string('status')->default('disponivel');
            $table->timestamps();
        });

        Schema::create('horarios', function (Blueprint $table) {
            $table->id();
            $table->foreignId('organizacao_id')->constrained('organizacoes');
            $table->foreignId('clinica_id')->constrained('clinicas');
            $table->unsignedTinyInteger('dia_semana');
            $table->time('hora_inicio');
            $table->time('hora_fim');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('horarios');
        Schema::dropIfExists('cadeiras');
        Schema::dropIfExists('clinicas');
    }
};
