<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('unidades', function (Blueprint $table) {
            if (Schema::hasColumn('unidades', 'horarios_funcionamento')) {
                $table->dropColumn('horarios_funcionamento');
            }
        });
    }

    public function down(): void
    {
        Schema::table('unidades', function (Blueprint $table) {
            if (!Schema::hasColumn('unidades', 'horarios_funcionamento')) {
                $table->string('horarios_funcionamento')->nullable();
            }
        });
    }
};
