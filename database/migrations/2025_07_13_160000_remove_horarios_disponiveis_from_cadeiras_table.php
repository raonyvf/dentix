<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('cadeiras', function (Blueprint $table) {
            if (Schema::hasColumn('cadeiras', 'horarios_disponiveis')) {
                $table->dropColumn('horarios_disponiveis');
            }
        });
    }

    public function down(): void
    {
        Schema::table('cadeiras', function (Blueprint $table) {
            if (!Schema::hasColumn('cadeiras', 'horarios_disponiveis')) {
                $table->string('horarios_disponiveis')->nullable();
            }
        });
    }
};
