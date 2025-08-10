<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('pessoas', function (Blueprint $table) {
            $table->string('normalized_name')->nullable();
            $table->string('digits_phone')->nullable();
            $table->string('digits_whatsapp')->nullable();
            $table->string('digits_cpf')->nullable();
        });

        $pessoas = DB::table('pessoas')
            ->select('id', 'primeiro_nome', 'nome_meio', 'ultimo_nome', 'phone', 'whatsapp', 'cpf')
            ->get();

        foreach ($pessoas as $pessoa) {
            $name = trim($pessoa->primeiro_nome . ' ' . ($pessoa->nome_meio ? $pessoa->nome_meio . ' ' : '') . $pessoa->ultimo_nome);
            $normalized = Str::of($name)->ascii()->lower();

            DB::table('pessoas')->where('id', $pessoa->id)->update([
                'normalized_name' => $normalized,
                'digits_phone' => preg_replace('/\D/', '', $pessoa->phone ?? ''),
                'digits_whatsapp' => preg_replace('/\D/', '', $pessoa->whatsapp ?? ''),
                'digits_cpf' => preg_replace('/\D/', '', $pessoa->cpf ?? ''),
            ]);
        }

        if (DB::getDriverName() === 'pgsql') {
            DB::statement('CREATE EXTENSION IF NOT EXISTS "pg_trgm";');
            DB::statement('CREATE INDEX pessoas_normalized_name_gin ON pessoas USING gin (normalized_name gin_trgm_ops);');
            DB::statement('CREATE INDEX pessoas_digits_phone_gin ON pessoas USING gin (digits_phone gin_trgm_ops);');
            DB::statement('CREATE INDEX pessoas_digits_whatsapp_gin ON pessoas USING gin (digits_whatsapp gin_trgm_ops);');
            DB::statement('CREATE INDEX pessoas_digits_cpf_gin ON pessoas USING gin (digits_cpf gin_trgm_ops);');
        } else {
            Schema::table('pessoas', function (Blueprint $table) {
                $table->index('normalized_name');
                $table->index('digits_phone');
                $table->index('digits_whatsapp');
                $table->index('digits_cpf');
            });
        }
    }

    public function down(): void
    {
        if (DB::getDriverName() === 'pgsql') {
            DB::statement('DROP INDEX IF EXISTS pessoas_normalized_name_gin;');
            DB::statement('DROP INDEX IF EXISTS pessoas_digits_phone_gin;');
            DB::statement('DROP INDEX IF EXISTS pessoas_digits_whatsapp_gin;');
            DB::statement('DROP INDEX IF EXISTS pessoas_digits_cpf_gin;');
        } else {
            Schema::table('pessoas', function (Blueprint $table) {
                $table->dropIndex(['normalized_name']);
                $table->dropIndex(['digits_phone']);
                $table->dropIndex(['digits_whatsapp']);
                $table->dropIndex(['digits_cpf']);
            });
        }

        Schema::table('pessoas', function (Blueprint $table) {
            $table->dropColumn(['normalized_name', 'digits_phone', 'digits_whatsapp', 'digits_cpf']);
        });
    }
};
