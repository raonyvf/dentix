<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('cep')->nullable()->after('endereco');
            $table->string('cidade')->nullable()->after('cep');
            $table->string('estado')->nullable()->after('cidade');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['cep', 'cidade', 'estado']);
        });
    }
};
