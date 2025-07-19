<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('logradouro')->nullable()->after('phone');
            $table->string('cpf')->nullable()->after('logradouro');
            $table->boolean('dentista')->default(false)->after('cpf');
            $table->string('cro')->nullable()->after('dentista');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['logradouro', 'cpf', 'dentista', 'cro']);
        });
    }
};
