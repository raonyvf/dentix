<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::rename('clinic_profissional', 'clinica_profissional');
    }

    public function down(): void
    {
        Schema::rename('clinica_profissional', 'clinic_profissional');
    }
};
