<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Profissional;
use App\Models\Person;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('profissionais', function (Blueprint $table) {
            $table->foreignId('person_id')->nullable()->after('organization_id')->constrained('people');
        });

        if (Schema::hasTable('profissionais')) {
            $rows = Profissional::withoutGlobalScopes()->get();
            foreach ($rows as $prof) {
                $person = Person::create([
                    'organization_id' => $prof->organization_id,
                    'first_name' => $prof->nome,
                    'middle_name' => $prof->nome_meio,
                    'last_name' => $prof->ultimo_nome,
                    'data_nascimento' => $prof->data_nascimento,
                    'sexo' => $prof->sexo,
                    'naturalidade' => $prof->naturalidade,
                    'nacionalidade' => $prof->nacionalidade,
                    'cpf' => $prof->cpf,
                    'rg' => $prof->rg,
                    'email' => $prof->email,
                    'phone' => $prof->telefone,
                    'cep' => $prof->cep,
                    'logradouro' => $prof->logradouro,
                    'numero' => $prof->numero,
                    'complemento' => $prof->complemento,
                    'bairro' => $prof->bairro,
                    'cidade' => $prof->cidade,
                    'estado' => $prof->estado,
                    'photo_path' => $prof->foto_path,
                ]);
                $prof->person_id = $person->id;
                $prof->save();
            }
        }

        Schema::table('profissionais', function (Blueprint $table) {
            $table->dropColumn([
                'nome','nome_meio','ultimo_nome','data_nascimento','sexo','naturalidade','nacionalidade','foto_path','cpf','rg','email','telefone','cep','logradouro','numero','complemento','bairro','cidade','estado'
            ]);
        });
    }

    public function down(): void
    {
        Schema::table('profissionais', function (Blueprint $table) {
            $table->string('nome')->nullable();
            $table->string('nome_meio')->nullable();
            $table->string('ultimo_nome')->nullable();
            $table->date('data_nascimento')->nullable();
            $table->string('sexo')->nullable();
            $table->string('naturalidade')->nullable();
            $table->string('nacionalidade')->nullable();
            $table->string('foto_path')->nullable();
            $table->string('cpf')->nullable();
            $table->string('rg')->nullable();
            $table->string('email')->nullable();
            $table->string('telefone')->nullable();
            $table->string('cep')->nullable();
            $table->string('logradouro')->nullable();
            $table->string('numero')->nullable();
            $table->string('complemento')->nullable();
            $table->string('bairro')->nullable();
            $table->string('cidade')->nullable();
            $table->string('estado')->nullable();
            $table->dropForeign(['person_id']);
            $table->dropColumn('person_id');
        });
    }
};
