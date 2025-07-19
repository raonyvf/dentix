<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('cadeiras', function (Blueprint $table) {
            if (Schema::hasColumn('cadeiras', 'especialidade')) {
                $table->dropColumn('especialidade');
            }
        });
    }

    public function down(): void
    {
        Schema::table('cadeiras', function (Blueprint $table) {
            if (!Schema::hasColumn('cadeiras', 'especialidade')) {
                $table->string('especialidade')->nullable();
            }
        });
    }
};
