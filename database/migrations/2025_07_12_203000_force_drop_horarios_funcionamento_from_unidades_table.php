<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasColumn('unidades', 'horarios_funcionamento')) {
            Schema::table('unidades', function (Blueprint $table) {
                $table->dropColumn('horarios_funcionamento');
            });
        }
    }

    public function down(): void
    {
        Schema::table('unidades', function (Blueprint $table) {
            $table->string('horarios_funcionamento')->nullable();
        });
    }
};
