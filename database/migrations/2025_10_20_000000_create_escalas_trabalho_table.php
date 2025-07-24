<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('escalas_trabalho', function (Blueprint $table) {
            $table->id();
            $table->foreignId('organization_id')->nullable()->constrained('organizations');
            $table->foreignId('clinic_id')->constrained('clinics');
            $table->foreignId('cadeira_id')->constrained('cadeiras');
            $table->foreignId('profissional_id')->constrained('profissionais');
            $table->date('semana');
            $table->enum('dia_semana', ['segunda','terca','quarta','quinta','sexta','sabado','domingo']);
            $table->time('hora_inicio');
            $table->time('hora_fim');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('escalas_trabalho');
    }
};
