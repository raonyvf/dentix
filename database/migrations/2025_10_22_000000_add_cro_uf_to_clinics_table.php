<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('clinics', function (Blueprint $table) {
            $table->string('cro_uf')->nullable()->after('cro');
        });
    }

    public function down(): void
    {
        Schema::table('clinics', function (Blueprint $table) {
            $table->dropColumn('cro_uf');
        });
    }
};
