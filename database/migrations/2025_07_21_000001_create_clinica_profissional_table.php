<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('clinica_profissional', function (Blueprint $table) {
            $table->id();
            $table->foreignId('clinica_id')->constrained('clinics');
            $table->foreignId('profissional_id')->constrained('users');
            $table->enum('status', ['Ativo', 'Inativo'])->default('Ativo');
            $table->decimal('comissao', 5, 2)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('clinica_profissional');
    }
};
