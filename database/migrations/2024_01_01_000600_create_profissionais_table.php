<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('profissionais', function (Blueprint $table) {
            $table->id();
            $table->foreignId('organizacao_id')->constrained('organizacoes');
            $table->foreignId('pessoa_id')->constrained('pessoas');
            $table->foreignId('usuario_id')->nullable()->constrained('usuarios');
            $table->string('numero_funcionario')->nullable();
            $table->string('email_corporativo')->nullable();
            $table->date('data_admissao')->nullable();
            $table->date('data_demissao')->nullable();
            $table->string('tipo_contrato')->nullable();
            $table->date('data_inicio_contrato')->nullable();
            $table->date('data_fim_contrato')->nullable();
            $table->integer('total_horas_semanais')->nullable();
            $table->string('regime_trabalho')->nullable();
            $table->string('funcao')->nullable();
            $table->string('cargo')->nullable();
            $table->string('cro')->nullable();
            $table->string('cro_uf', 2)->nullable();
            $table->decimal('salario_fixo', 10, 2)->nullable();
            $table->string('salario_periodo')->nullable();
            $table->json('comissoes')->nullable();
            $table->json('conta')->nullable();
            $table->string('chave_pix')->nullable();
            $table->timestamps();
        });

        Schema::create('profissional_horarios', function (Blueprint $table) {
            $table->id();
            $table->foreignId('organizacao_id')->constrained('organizacoes');
            $table->foreignId('clinica_id')->constrained('clinicas');
            $table->foreignId('profissional_id')->constrained('profissionais');
            $table->unsignedTinyInteger('dia_semana');
            $table->time('hora_inicio');
            $table->time('hora_fim');
            $table->timestamps();
        });

        Schema::create('clinica_profissional', function (Blueprint $table) {
            $table->id();
            $table->foreignId('clinica_id')->constrained('clinicas');
            $table->foreignId('profissional_id')->constrained('profissionais');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('clinica_profissional');
        Schema::dropIfExists('profissional_horarios');
        Schema::dropIfExists('profissionais');
    }
};
