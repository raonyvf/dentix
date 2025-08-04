<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('pessoa_id')->nullable()->after('organization_id')->constrained('pessoas');
        });

        if (Schema::hasTable('users')) {
            $users = DB::table('users')->get();
            foreach ($users as $user) {
                $pessoaId = DB::table('pessoas')->insertGetId([
                    'organization_id' => $user->organization_id ?? 1,
                    'first_name' => $user->first_name ?? $user->name,
                    'middle_name' => $user->middle_name,
                    'last_name' => $user->last_name,
                    'data_nascimento' => $user->data_nascimento,
                    'sexo' => $user->sexo,
                    'naturalidade' => $user->naturalidade,
                    'nacionalidade' => $user->nacionalidade,
                    'cpf' => $user->cpf,
                    'rg' => $user->rg,
                    'phone' => $user->phone,
                    'email' => $user->email,
                    'cep' => $user->cep,
                    'logradouro' => $user->logradouro,
                    'numero' => $user->numero,
                    'complemento' => $user->complemento,
                    'bairro' => $user->bairro,
                    'cidade' => $user->cidade,
                    'estado' => $user->estado,
                    'photo_path' => $user->photo_path,
                ]);
                DB::table('users')->where('id', $user->id)->update(['pessoa_id' => $pessoaId]);
            }
        }

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'name','first_name','middle_name','last_name','data_nascimento','sexo','naturalidade','nacionalidade','cpf','rg','phone','logradouro','numero','complemento','bairro','cep','cidade','estado','photo_path'
            ]);
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('name')->nullable();
            $table->string('first_name')->nullable();
            $table->string('middle_name')->nullable();
            $table->string('last_name')->nullable();
            $table->date('data_nascimento')->nullable();
            $table->string('sexo')->nullable();
            $table->string('naturalidade')->nullable();
            $table->string('nacionalidade')->nullable();
            $table->string('cpf')->nullable();
            $table->string('rg')->nullable();
            $table->string('phone')->nullable();
            $table->string('logradouro')->nullable();
            $table->string('numero')->nullable();
            $table->string('complemento')->nullable();
            $table->string('bairro')->nullable();
            $table->string('cep')->nullable();
            $table->string('cidade')->nullable();
            $table->string('estado')->nullable();
            $table->string('photo_path')->nullable();
            $table->dropForeign(['pessoa_id']);
            $table->dropColumn('pessoa_id');
        });
    }
};
