<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('profissionais', function (Blueprint $table) {
            $table->id();
            $table->foreignId('organization_id')->constrained('organizations');
            $table->string('nome');
            $table->string('nome_meio')->nullable();
            $table->string('ultimo_nome');
            $table->date('data_nascimento')->nullable();
            $table->string('sexo')->nullable();
            $table->string('naturalidade')->nullable();
            $table->string('nacionalidade')->nullable();
            $table->string('foto_path')->nullable();
            $table->string('cpf')->nullable();
            $table->string('rg')->nullable();
            $table->string('email')->nullable();
            $table->string('telefone')->nullable();
            $table->string('cep')->nullable();
            $table->string('logradouro')->nullable();
            $table->string('numero')->nullable();
            $table->string('complemento')->nullable();
            $table->string('bairro')->nullable();
            $table->string('cidade')->nullable();
            $table->string('estado')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('profissionais');
    }
};
