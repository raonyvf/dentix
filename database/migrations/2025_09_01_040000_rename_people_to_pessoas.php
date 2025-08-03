<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (Schema::hasTable('people')) {
            Schema::rename('people', 'pessoas');
        }

        if (Schema::hasColumn('patients', 'person_id')) {
            Schema::table('patients', function (Blueprint $table) {
                $table->dropForeign(['person_id']);
                $table->renameColumn('person_id', 'pessoa_id');
                $table->foreign('pessoa_id')->references('id')->on('pessoas')->nullOnDelete();
            });
        }

        if (Schema::hasColumn('profissionais', 'person_id')) {
            Schema::table('profissionais', function (Blueprint $table) {
                $table->dropForeign(['person_id']);
                $table->renameColumn('person_id', 'pessoa_id');
                $table->foreign('pessoa_id')->references('id')->on('pessoas')->nullOnDelete();
            });
        }

        if (Schema::hasColumn('users', 'person_id')) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropForeign(['person_id']);
                $table->renameColumn('person_id', 'pessoa_id');
                $table->foreign('pessoa_id')->references('id')->on('pessoas')->nullOnDelete();
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('patients', 'pessoa_id')) {
            Schema::table('patients', function (Blueprint $table) {
                $table->dropForeign(['pessoa_id']);
                $table->renameColumn('pessoa_id', 'person_id');
                $table->foreign('person_id')->references('id')->on('people')->nullOnDelete();
            });
        }

        if (Schema::hasColumn('profissionais', 'pessoa_id')) {
            Schema::table('profissionais', function (Blueprint $table) {
                $table->dropForeign(['pessoa_id']);
                $table->renameColumn('pessoa_id', 'person_id');
                $table->foreign('person_id')->references('id')->on('people')->nullOnDelete();
            });
        }

        if (Schema::hasColumn('users', 'pessoa_id')) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropForeign(['pessoa_id']);
                $table->renameColumn('pessoa_id', 'person_id');
                $table->foreign('person_id')->references('id')->on('people')->nullOnDelete();
            });
        }

        if (Schema::hasTable('pessoas')) {
            Schema::rename('pessoas', 'people');
        }
    }
};
