<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('profissionais', function (Blueprint $table) {
            $table->string('numero_funcionario')->nullable()->after('user_id');
            $table->string('email_corporativo')->nullable()->after('numero_funcionario');
            $table->date('data_admissao')->nullable()->after('email_corporativo');
            $table->date('data_demissao')->nullable()->after('data_admissao');
            $table->string('tipo_contrato')->nullable()->after('data_demissao');
            $table->date('data_inicio_contrato')->nullable()->after('tipo_contrato');
            $table->date('data_fim_contrato')->nullable()->after('data_inicio_contrato');
            $table->integer('carga_horaria')->nullable()->after('data_fim_contrato');
            $table->integer('total_horas_semanais')->nullable()->after('carga_horaria');
            $table->string('regime_trabalho')->nullable()->after('total_horas_semanais');
            $table->string('funcao')->nullable()->after('regime_trabalho');
            $table->string('cargo')->nullable()->after('funcao');
            $table->string('cro')->nullable()->after('cargo');
            $table->string('cro_uf')->nullable()->after('cro');
            $table->decimal('salario_fixo', 10, 2)->nullable()->after('cro_uf');
            $table->string('salario_periodo')->nullable()->after('salario_fixo');
            $table->json('comissoes')->nullable()->after('salario_periodo');
            $table->json('conta')->nullable()->after('comissoes');
            $table->string('chave_pix')->nullable()->after('conta');
        });
    }

    public function down(): void
    {
        Schema::table('profissionais', function (Blueprint $table) {
            $table->dropColumn([
                'numero_funcionario','email_corporativo','data_admissao','data_demissao',
                'tipo_contrato','data_inicio_contrato','data_fim_contrato','carga_horaria',
                'total_horas_semanais','regime_trabalho','funcao','cargo','cro','cro_uf',
                'salario_fixo','salario_periodo','comissoes','conta','chave_pix'
            ]);
        });
    }
};
