<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('clinics', function (Blueprint $table) {
            if (Schema::hasColumn('clinics', 'responsavel')) {
                $table->renameColumn('responsavel', 'responsavel_tecnico');
            }
            if (!Schema::hasColumn('clinics', 'cro')) {
                $table->string('cro')->nullable()->after('responsavel_tecnico');
            }
            if (Schema::hasColumn('clinics', 'contato')) {
                $table->renameColumn('contato', 'telefone');
            }
            if (!Schema::hasColumn('clinics', 'email')) {
                $table->string('email')->nullable()->after('telefone');
            }
        });
    }

    public function down(): void
    {
        Schema::table('clinics', function (Blueprint $table) {
            if (Schema::hasColumn('clinics', 'responsavel_tecnico')) {
                $table->renameColumn('responsavel_tecnico', 'responsavel');
            }
            if (Schema::hasColumn('clinics', 'cro')) {
                $table->dropColumn('cro');
            }
            if (Schema::hasColumn('clinics', 'telefone')) {
                $table->renameColumn('telefone', 'contato');
            }
            if (Schema::hasColumn('clinics', 'email')) {
                $table->dropColumn('email');
            }
        });
    }
};
