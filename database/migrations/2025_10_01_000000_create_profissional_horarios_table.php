<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('profissional_horarios', function (Blueprint $table) {
            $table->id();
            $table->foreignId('profissional_id')->constrained('profissionais');
            $table->foreignId('clinic_id')->constrained('clinics');
            $table->foreignId('organization_id')->nullable()->constrained('organizations');
            $table->enum('dia_semana', ['segunda','terca','quarta','quinta','sexta','sabado','domingo']);
            $table->time('hora_inicio');
            $table->time('hora_fim');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('profissional_horarios');
    }
};
