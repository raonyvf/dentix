<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('usuarios', function (Blueprint $table) {
            $table->id();
            $table->foreignId('organization_id')->constrained('organizacoes');
            $table->foreignId('pessoa_id')->nullable()->constrained('pessoas');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->boolean('dentista')->default(false);
            $table->string('cro')->nullable();
            $table->string('cargo')->nullable();
            $table->string('especialidade')->nullable();
            $table->decimal('salario_base', 10, 2)->nullable();
            $table->boolean('must_change_password')->default(false);
            $table->rememberToken();
            $table->timestamps();
        });

        Schema::create('clinica_usuario', function (Blueprint $table) {
            $table->id();
            $table->foreignId('clinica_id')->constrained('clinicas');
            $table->foreignId('usuario_id')->constrained('usuarios');
            $table->foreignId('perfil_id')->nullable()->constrained('perfis');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('clinica_usuario');
        Schema::dropIfExists('usuarios');
    }
};
