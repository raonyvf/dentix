<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('clinics', function (Blueprint $table) {
            if (Schema::hasColumn('clinics', 'plano_id')) {
                $table->dropForeign(['plano_id']);
                $table->dropColumn('plano_id');
            }
            if (!Schema::hasColumn('clinics', 'endereco')) {
                $table->string('endereco')->nullable();
            }
            if (!Schema::hasColumn('clinics', 'cidade')) {
                $table->string('cidade')->nullable();
            }
            if (!Schema::hasColumn('clinics', 'estado')) {
                $table->string('estado')->nullable();
            }
            if (!Schema::hasColumn('clinics', 'contato')) {
                $table->string('contato')->nullable();
            }
        });

        if (Schema::hasTable('cadeiras') && Schema::hasColumn('cadeiras', 'unidade_id')) {
            Schema::table('cadeiras', function (Blueprint $table) {
                $table->dropForeign(['unidade_id']);
                $table->dropColumn('unidade_id');
            });
        }

        if (Schema::hasTable('horarios') && Schema::hasColumn('horarios', 'unidade_id')) {
            Schema::table('horarios', function (Blueprint $table) {
                $table->dropForeign(['unidade_id']);
                $table->dropColumn('unidade_id');
            });
        }

        Schema::dropIfExists('unidades');
    }

    public function down(): void
    {
        Schema::create('unidades', function (Blueprint $table) {
            $table->id();
            $table->foreignId('clinic_id')->constrained('clinics');
            $table->string('nome');
            $table->string('endereco');
            $table->string('cidade');
            $table->string('estado');
            $table->string('contato');
            $table->timestamps();
        });

        Schema::table('horarios', function (Blueprint $table) {
            $table->foreignId('unidade_id')->constrained('unidades');
        });

        Schema::table('cadeiras', function (Blueprint $table) {
            $table->foreignId('unidade_id')->constrained('unidades');
        });

        Schema::table('clinics', function (Blueprint $table) {
            $table->foreignId('plano_id')->nullable()->constrained('planos');
            $table->dropColumn(['endereco', 'cidade', 'estado', 'contato']);
        });
    }
};
