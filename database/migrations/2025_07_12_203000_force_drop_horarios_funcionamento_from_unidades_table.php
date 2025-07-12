<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasColumn('unidades', 'horarios_funcionamento')) {
            DB::statement('ALTER TABLE unidades DROP COLUMN horarios_funcionamento');
        }
    }

    public function down(): void
    {
        Schema::table('unidades', function (Blueprint $table) {
            $table->string('horarios_funcionamento')->nullable();
        });
    }
};
