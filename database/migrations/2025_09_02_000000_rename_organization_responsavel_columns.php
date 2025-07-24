<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Organization;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('organizations', function (Blueprint $table) {
            if (Schema::hasColumn('organizations', 'responsavel_nome')) {
                $table->string('responsavel_first_name')->nullable()->after('telefone');
                $table->string('responsavel_middle_name')->nullable()->after('responsavel_first_name');
                $table->string('responsavel_last_name')->nullable()->after('responsavel_middle_name');
            }
        });

        if (Schema::hasColumn('organizations', 'responsavel_nome')) {
            foreach (Organization::withoutGlobalScopes()->get() as $org) {
                $org->responsavel_first_name = $org->responsavel_nome;
                $org->responsavel_middle_name = $org->responsavel_nome_meio;
                $org->responsavel_last_name = $org->responsavel_ultimo_nome;
                $org->save();
            }
            Schema::table('organizations', function (Blueprint $table) {
                $table->dropColumn(['responsavel_nome','responsavel_nome_meio','responsavel_ultimo_nome']);
            });
        }
    }

    public function down(): void
    {
        Schema::table('organizations', function (Blueprint $table) {
            if (!Schema::hasColumn('organizations', 'responsavel_nome')) {
                $table->string('responsavel_nome')->nullable()->after('telefone');
                $table->string('responsavel_nome_meio')->nullable()->after('responsavel_nome');
                $table->string('responsavel_ultimo_nome')->nullable()->after('responsavel_nome_meio');
            }
        });

        if (Schema::hasColumn('organizations', 'responsavel_first_name')) {
            foreach (Organization::withoutGlobalScopes()->get() as $org) {
                $org->responsavel_nome = $org->responsavel_first_name;
                $org->responsavel_nome_meio = $org->responsavel_middle_name;
                $org->responsavel_ultimo_nome = $org->responsavel_last_name;
                $org->save();
            }
            Schema::table('organizations', function (Blueprint $table) {
                $table->dropColumn(['responsavel_first_name','responsavel_middle_name','responsavel_last_name']);
            });
        }
    }
};
