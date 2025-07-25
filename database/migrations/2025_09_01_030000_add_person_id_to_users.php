<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\User;
use App\Models\Person;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('person_id')->nullable()->after('organization_id')->constrained('people');
        });

        if (Schema::hasTable('users')) {
            $users = User::withoutGlobalScopes()->get();
            foreach ($users as $user) {
                $person = Person::create([
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
                $user->person_id = $person->id;
                $user->save();
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
            $table->dropForeign(['person_id']);
            $table->dropColumn('person_id');
        });
    }
};
