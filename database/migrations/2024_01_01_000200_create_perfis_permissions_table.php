<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('perfis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('organizacao_id')->constrained('organizacoes');
            $table->string('nome');
            $table->timestamps();
        });

        Schema::create('permissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('perfil_id')->constrained('perfis');
            $table->string('modulo');
            $table->boolean('leitura')->default(false);
            $table->boolean('escrita')->default(false);
            $table->boolean('atualizacao')->default(false);
            $table->boolean('exclusao')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('permissions');
        Schema::dropIfExists('perfis');
    }
};
