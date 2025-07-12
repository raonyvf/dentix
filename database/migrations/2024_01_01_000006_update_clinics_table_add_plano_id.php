<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('clinics', function (Blueprint $table) {
            $table->foreignId('plano_id')->nullable()->constrained('planos');
            $table->dropColumn('plano');
            $table->dropColumn('idioma_preferido');
        });
    }

    public function down(): void
    {
        Schema::table('clinics', function (Blueprint $table) {
            $table->string('plano');
            $table->string('idioma_preferido');
            $table->dropForeign(['plano_id']);
            $table->dropColumn('plano_id');
        });
    }
};
