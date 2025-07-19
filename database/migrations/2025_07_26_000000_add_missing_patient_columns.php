<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('patients', function (Blueprint $table) {
            if (! Schema::hasColumn('patients', 'first_name')) {
                $table->string('first_name')->nullable()->after('clinic_id');
            }
            if (! Schema::hasColumn('patients', 'middle_name')) {
                $table->string('middle_name')->nullable()->after('first_name');
            }
            if (! Schema::hasColumn('patients', 'last_name')) {
                $table->string('last_name')->nullable()->after('middle_name');
            }
            if (! Schema::hasColumn('patients', 'responsavel_first_name')) {
                $table->string('responsavel_first_name')->nullable()->after('responsavel');
            }
            if (! Schema::hasColumn('patients', 'responsavel_middle_name')) {
                $table->string('responsavel_middle_name')->nullable()->after('responsavel_first_name');
            }
            if (! Schema::hasColumn('patients', 'responsavel_last_name')) {
                $table->string('responsavel_last_name')->nullable()->after('responsavel_middle_name');
            }
            if (! Schema::hasColumn('patients', 'cpf')) {
                $table->string('cpf')->nullable()->after('nome');
            }
            if (! Schema::hasColumn('patients', 'menor_idade')) {
                $table->boolean('menor_idade')->default(false)->after('idade');
            }
            if (! Schema::hasColumn('patients', 'responsavel_cpf')) {
                $table->string('responsavel_cpf')->nullable()->after('responsavel_last_name');
            }
            if (! Schema::hasColumn('patients', 'email')) {
                $table->string('email')->nullable()->after('telefone');
            }
            if (! Schema::hasColumn('patients', 'cep')) {
                $table->string('cep')->nullable()->after('email');
            }
            if (! Schema::hasColumn('patients', 'endereco_rua')) {
                $table->string('endereco_rua')->nullable()->after('cep');
            }
            if (! Schema::hasColumn('patients', 'numero')) {
                $table->string('numero')->nullable()->after('endereco_rua');
            }
            if (! Schema::hasColumn('patients', 'complemento')) {
                $table->string('complemento')->nullable()->after('numero');
            }
            if (! Schema::hasColumn('patients', 'bairro')) {
                $table->string('bairro')->nullable()->after('complemento');
            }
            if (! Schema::hasColumn('patients', 'cidade')) {
                $table->string('cidade')->nullable()->after('bairro');
            }
            if (! Schema::hasColumn('patients', 'estado')) {
                $table->string('estado')->nullable()->after('cidade');
            }
            if (! Schema::hasColumn('patients', 'data_nascimento')) {
                $table->date('data_nascimento')->nullable()->after('responsavel');
            }
        });
    }

    public function down(): void
    {
        Schema::table('patients', function (Blueprint $table) {
            $columns = [
                'first_name',
                'middle_name',
                'last_name',
                'responsavel_first_name',
                'responsavel_middle_name',
                'responsavel_last_name',
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
                'estado',
                'data_nascimento',
            ];
            foreach ($columns as $column) {
                if (Schema::hasColumn('patients', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
