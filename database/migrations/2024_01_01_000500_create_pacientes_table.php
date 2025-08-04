<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('pacientes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('organization_id')->constrained('organizacoes');
            $table->foreignId('usuario_id')->nullable()->constrained('usuarios');
            $table->foreignId('pessoa_id')->constrained('pessoas');
            $table->boolean('menor_idade')->default(false);
            $table->string('responsavel_first_name')->nullable();
            $table->string('responsavel_middle_name')->nullable();
            $table->string('responsavel_last_name')->nullable();
            $table->string('responsavel_cpf')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pacientes');
    }
};
