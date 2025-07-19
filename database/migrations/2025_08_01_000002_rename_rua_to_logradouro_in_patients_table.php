<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasColumn('patients', 'rua') && !Schema::hasColumn('patients', 'logradouro')) {
            Schema::table('patients', function (Blueprint $table) {
                $table->renameColumn('rua', 'logradouro');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('patients', 'logradouro') && !Schema::hasColumn('patients', 'rua')) {
            Schema::table('patients', function (Blueprint $table) {
                $table->renameColumn('logradouro', 'rua');
            });
        }
    }
};
