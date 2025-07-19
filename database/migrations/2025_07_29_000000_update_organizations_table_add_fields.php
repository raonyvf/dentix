<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Organization;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('organizations', function (Blueprint $table) {
            $table->string('responsavel_nome')->nullable()->after('telefone');
            $table->string('responsavel_nome_meio')->nullable()->after('responsavel_nome');
            $table->string('responsavel_ultimo_nome')->nullable()->after('responsavel_nome_meio');
            $table->string('cep')->nullable()->after('responsavel_ultimo_nome');
            $table->string('rua')->nullable()->after('cep');
            $table->string('numero')->nullable()->after('rua');
            $table->string('complemento')->nullable()->after('numero');
            $table->string('bairro')->nullable()->after('complemento');
            $table->string('cidade')->nullable()->after('bairro');
            $table->string('estado')->nullable()->after('cidade');
        });

        if (Schema::hasColumn('organizations', 'endereco_faturamento')) {
            foreach (Organization::all() as $organization) {
                $address = $organization->endereco_faturamento ?? [];
                $organization->cep = $address['cep'] ?? null;
                $organization->rua = $address['logradouro'] ?? null;
                $organization->numero = $address['numero'] ?? null;
                $organization->complemento = $address['complemento'] ?? null;
                $organization->bairro = $address['bairro'] ?? null;
                $organization->cidade = $address['cidade'] ?? null;
                $organization->estado = $address['estado'] ?? null;
                $organization->save();
            }
            Schema::table('organizations', function (Blueprint $table) {
                $table->dropColumn('endereco_faturamento');
            });
        }
    }

    public function down(): void
    {
        Schema::table('organizations', function (Blueprint $table) {
            $table->json('endereco_faturamento')->nullable()->after('telefone');
        });

        foreach (Organization::all() as $organization) {
            $organization->endereco_faturamento = [
                'cep' => $organization->cep,
                'logradouro' => $organization->rua,
                'numero' => $organization->numero,
                'complemento' => $organization->complemento,
                'bairro' => $organization->bairro,
                'cidade' => $organization->cidade,
                'estado' => $organization->estado,
            ];
            $organization->save();
        }

        Schema::table('organizations', function (Blueprint $table) {
            $table->dropColumn([
                'responsavel_nome',
                'responsavel_nome_meio',
                'responsavel_ultimo_nome',
                'cep',
                'rua',
                'numero',
                'complemento',
                'bairro',
                'cidade',
                'estado',
            ]);
        });
    }
};
