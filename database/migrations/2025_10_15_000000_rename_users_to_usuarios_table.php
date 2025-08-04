<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (Schema::hasTable('users')) {
            Schema::rename('users', 'usuarios');
        }

        if (Schema::hasTable('patients') && Schema::hasColumn('patients', 'user_id')) {
            Schema::table('patients', function (Blueprint $table) {
                $table->dropForeign(['user_id']);
                $table->renameColumn('user_id', 'usuario_id');
                $table->foreign('usuario_id')->references('id')->on('usuarios')->onDelete('cascade');
            });
        }

        if (Schema::hasTable('profissionais') && Schema::hasColumn('profissionais', 'user_id')) {
            Schema::table('profissionais', function (Blueprint $table) {
                $table->dropForeign(['user_id']);
                $table->renameColumn('user_id', 'usuario_id');
                $table->foreign('usuario_id')->references('id')->on('usuarios')->onDelete('cascade');
            });
        }

        if (Schema::hasTable('clinic_user') && Schema::hasColumn('clinic_user', 'user_id')) {
            Schema::table('clinic_user', function (Blueprint $table) {
                $table->dropForeign(['user_id']);
                $table->renameColumn('user_id', 'usuario_id');
                $table->foreign('usuario_id')->references('id')->on('usuarios')->onDelete('cascade');
            });
        }

        if (Schema::hasTable('sessions') && Schema::hasColumn('sessions', 'user_id')) {
            Schema::table('sessions', function (Blueprint $table) {
                $table->renameColumn('user_id', 'usuario_id');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('sessions') && Schema::hasColumn('sessions', 'usuario_id')) {
            Schema::table('sessions', function (Blueprint $table) {
                $table->renameColumn('usuario_id', 'user_id');
            });
        }

        if (Schema::hasTable('clinic_user') && Schema::hasColumn('clinic_user', 'usuario_id')) {
            Schema::table('clinic_user', function (Blueprint $table) {
                $table->dropForeign(['usuario_id']);
                $table->renameColumn('usuario_id', 'user_id');
                $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            });
        }

        if (Schema::hasTable('profissionais') && Schema::hasColumn('profissionais', 'usuario_id')) {
            Schema::table('profissionais', function (Blueprint $table) {
                $table->dropForeign(['usuario_id']);
                $table->renameColumn('usuario_id', 'user_id');
                $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            });
        }

        if (Schema::hasTable('patients') && Schema::hasColumn('patients', 'usuario_id')) {
            Schema::table('patients', function (Blueprint $table) {
                $table->dropForeign(['usuario_id']);
                $table->renameColumn('usuario_id', 'user_id');
                $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            });
        }

        if (Schema::hasTable('usuarios')) {
            Schema::rename('usuarios', 'users');
        }
    }
};
