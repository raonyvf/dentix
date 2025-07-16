<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('organizations', function (Blueprint $table) {
            $table->id();
            $table->string('nome_fantasia');
            $table->string('razao_social')->nullable();
            $table->string('cnpj');
            $table->string('email')->nullable();
            $table->string('telefone')->nullable();
            $table->json('endereco_faturamento')->nullable();
            $table->string('logo_url')->nullable();
            $table->enum('status', ['ativo', 'inativo', 'suspenso'])->default('ativo');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('organizations');
    }
};