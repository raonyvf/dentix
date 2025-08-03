<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Profissional;
use App\Models\Pessoa;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('profissionais', function (Blueprint $table) {
            $table->foreignId('pessoa_id')->nullable()->after('organization_id')->constrained('pessoas');
        });

        if (Schema::hasTable('profissionais')) {
            $rows = Profissional::withoutGlobalScopes()->get();
            foreach ($rows as $prof) {
                $pessoa = Pessoa::create([
                    'organization_id' => $prof->organization_id,
                    'first_name' => $prof->first_name,
                    'middle_name' => $prof->middle_name,
                    'last_name' => $prof->last_name,
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
                $prof->pessoa_id = $pessoa->id;
                $prof->save();
            }
        }

        Schema::table('profissionais', function (Blueprint $table) {
            $table->dropColumn([
                'first_name','middle_name','last_name','data_nascimento','sexo','naturalidade','nacionalidade','foto_path','cpf','rg','email','telefone','cep','logradouro','numero','complemento','bairro','cidade','estado'
            ]);
        });
    }

    public function down(): void
    {
        Schema::table('profissionais', function (Blueprint $table) {
            $table->string('first_name')->nullable();
            $table->string('middle_name')->nullable();
            $table->string('last_name')->nullable();
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
            $table->dropForeign(['pessoa_id']);
            $table->dropColumn('pessoa_id');
        });
    }
};
