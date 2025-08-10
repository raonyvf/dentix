<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::rename('permissions', 'permissoes');
    }

    public function down(): void
    {
        Schema::rename('permissoes', 'permissions');
    }
};
