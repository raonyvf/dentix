<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Carbon;

return new class extends Migration {
    public function up(): void
    {
        $profissionais = DB::table('profissionais')->select('id', 'comissoes')->whereNotNull('comissoes')->get();

        foreach ($profissionais as $profissional) {
            $comissoes = json_decode($profissional->comissoes, true) ?? [];
            foreach ($comissoes as $clinicaId => $vals) {
                DB::table('profissional_comissoes')->insert([
                    'profissional_id' => $profissional->id,
                    'clinica_id' => $clinicaId,
                    'comissao' => $vals['comissao'] ?? null,
                    'protese' => $vals['protese'] ?? null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        Schema::table('profissionais', function (Blueprint $table) {
            $table->dropColumn('comissoes');
        });
    }

    public function down(): void
    {
        Schema::table('profissionais', function (Blueprint $table) {
            $table->json('comissoes')->nullable();
        });

        $dados = DB::table('profissional_comissoes')->get();
        $agrupados = [];

        foreach ($dados as $row) {
            $agrupados[$row->profissional_id][$row->clinica_id] = [
                'comissao' => $row->comissao,
                'protese' => $row->protese,
            ];
        }

        foreach ($agrupados as $profId => $comissao) {
            DB::table('profissionais')->where('id', $profId)->update([
                'comissoes' => json_encode($comissao),
            ]);
        }
    }
};
