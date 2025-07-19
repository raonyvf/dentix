<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasColumn('users', 'endereco') && !Schema::hasColumn('users', 'logradouro')) {
            Schema::table('users', function (Blueprint $table) {
                $table->renameColumn('endereco', 'logradouro');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('users', 'logradouro') && !Schema::hasColumn('users', 'endereco')) {
            Schema::table('users', function (Blueprint $table) {
                $table->renameColumn('logradouro', 'endereco');
            });
        }
    }
};
