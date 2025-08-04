<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::rename('profiles', 'perfis');

        Schema::table('permissions', function (Blueprint $table) {
            $table->dropForeign(['profile_id']);
            $table->renameColumn('profile_id', 'perfil_id');
            $table->foreign('perfil_id')->references('id')->on('perfis')->onDelete('cascade');
        });

        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'profile_id')) {
                $table->dropForeign(['profile_id']);
                $table->renameColumn('profile_id', 'perfil_id');
                $table->foreign('perfil_id')->references('id')->on('perfis');
            }
        });

        Schema::table('clinic_user', function (Blueprint $table) {
            if (Schema::hasColumn('clinic_user', 'profile_id')) {
                $table->dropForeign(['profile_id']);
                $table->renameColumn('profile_id', 'perfil_id');
                $table->foreign('perfil_id')->references('id')->on('perfis');
            }
        });
    }

    public function down(): void
    {
        Schema::table('clinic_user', function (Blueprint $table) {
            if (Schema::hasColumn('clinic_user', 'perfil_id')) {
                $table->dropForeign(['perfil_id']);
                $table->renameColumn('perfil_id', 'profile_id');
                $table->foreign('profile_id')->references('id')->on('profiles');
            }
        });

        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'perfil_id')) {
                $table->dropForeign(['perfil_id']);
                $table->renameColumn('perfil_id', 'profile_id');
                $table->foreign('profile_id')->references('id')->on('profiles');
            }
        });

        Schema::table('permissions', function (Blueprint $table) {
            $table->dropForeign(['perfil_id']);
            $table->renameColumn('perfil_id', 'profile_id');
            $table->foreign('profile_id')->references('id')->on('profiles')->onDelete('cascade');
        });

        Schema::rename('perfis', 'profiles');
    }
};
