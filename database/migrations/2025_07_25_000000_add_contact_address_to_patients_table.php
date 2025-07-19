<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('patients', function (Blueprint $table) {
            $table->string('cpf')->nullable()->after('nome');
            $table->boolean('menor_idade')->default(false)->after('idade');
            $table->string('responsavel_cpf')->nullable()->after('responsavel');
            $table->string('email')->nullable()->after('telefone');
            $table->string('cep')->nullable()->after('email');
            $table->string('endereco_rua')->nullable()->after('cep');
            $table->string('numero')->nullable()->after('endereco_rua');
            $table->string('complemento')->nullable()->after('numero');
            $table->string('bairro')->nullable()->after('complemento');
            $table->string('cidade')->nullable()->after('bairro');
            $table->string('estado')->nullable()->after('cidade');
        });
    }

    public function down(): void
    {
        Schema::table('patients', function (Blueprint $table) {
            $table->dropColumn([
                'cpf',
                'menor_idade',
                'responsavel_cpf',
                'email',
                'cep',
                'endereco_rua',
                'numero',
                'complemento',
                'bairro',
                'cidade',
                'estado'
            ]);
        });
    }
};
