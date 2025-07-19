<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasColumn('clinics', 'endereco') && !Schema::hasColumn('clinics', 'logradouro')) {
            Schema::table('clinics', function (Blueprint $table) {
                $table->renameColumn('endereco', 'logradouro');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('clinics', 'logradouro') && !Schema::hasColumn('clinics', 'endereco')) {
            Schema::table('clinics', function (Blueprint $table) {
                $table->renameColumn('logradouro', 'endereco');
            });
        }
    }
};
