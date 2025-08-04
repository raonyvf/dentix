<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('clinica_profissional', function (Blueprint $table) {
            $table->id();
            $table->foreignId('clinic_id')->constrained('clinics');
            $table->foreignId('profissional_id')->constrained('profissionais');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('clinica_profissional');
    }
};
