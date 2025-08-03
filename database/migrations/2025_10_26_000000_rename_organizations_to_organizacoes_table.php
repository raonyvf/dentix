<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::rename('organizations', 'organizacoes');
    }

    public function down(): void
    {
        Schema::rename('organizacoes', 'organizations');
    }
};
