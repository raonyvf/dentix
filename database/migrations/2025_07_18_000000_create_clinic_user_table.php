<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('clinic_user', function (Blueprint $table) {
            $table->id();
            $table->foreignId('clinic_id')->nullable()->constrained('clinics');
            $table->foreignId('user_id')->constrained('users');
            $table->foreignId('profile_id')->nullable()->constrained('profiles');
            $table->timestamps();
        });

        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'clinic_id')) {
                $table->dropForeign(['clinic_id']);
                $table->dropColumn('clinic_id');
            }
            if (Schema::hasColumn('users', 'profile_id')) {
                $table->dropForeign(['profile_id']);
                $table->dropColumn('profile_id');
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('clinic_id')->nullable()->constrained('clinics');
            $table->foreignId('profile_id')->nullable()->constrained('profiles');
        });

        Schema::dropIfExists('clinic_user');
    }
};
