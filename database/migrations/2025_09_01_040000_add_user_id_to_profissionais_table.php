<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('profissionais', function (Blueprint $table) {
            if (!Schema::hasColumn('profissionais', 'user_id')) {
                $table->foreignId('user_id')->nullable()->after('organization_id')->constrained('users');
            }
        });
    }

    public function down(): void
    {
        Schema::table('profissionais', function (Blueprint $table) {
            if (Schema::hasColumn('profissionais', 'user_id')) {
                $table->dropForeign(['user_id']);
                $table->dropColumn('user_id');
            }
        });
    }
};
