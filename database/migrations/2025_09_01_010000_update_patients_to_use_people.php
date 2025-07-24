<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Patient;
use App\Models\Person;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('patients', function (Blueprint $table) {
            $table->foreignId('person_id')->nullable()->after('organization_id')->constrained('people');
        });

        // move data
        if (Schema::hasTable('patients')) {
            $patients = Patient::withoutGlobalScopes()->get();
            foreach ($patients as $patient) {
                $person = Person::create([
                    'organization_id' => $patient->organization_id,
                    'first_name' => $patient->nome,
                    'middle_name' => $patient->nome_meio,
                    'last_name' => $patient->ultimo_nome,
                    'data_nascimento' => $patient->data_nascimento,
                    'cpf' => $patient->cpf,
                    'phone' => $patient->telefone,
                    'whatsapp' => $patient->whatsapp,
                    'email' => $patient->email,
                    'cep' => $patient->cep,
                    'logradouro' => $patient->logradouro,
                    'numero' => $patient->numero,
                    'complemento' => $patient->complemento,
                    'bairro' => $patient->bairro,
                    'cidade' => $patient->cidade,
                    'estado' => $patient->estado,
                ]);
                $patient->person_id = $person->id;
                $patient->save();
            }
        }

        Schema::table('patients', function (Blueprint $table) {
            $table->dropColumn([
                'nome','nome_meio','ultimo_nome','data_nascimento','cpf','telefone','whatsapp','email','cep','logradouro','numero','complemento','bairro','cidade','estado'
            ]);
        });
    }

    public function down(): void
    {
        Schema::table('patients', function (Blueprint $table) {
            $table->string('nome')->nullable();
            $table->string('nome_meio')->nullable();
            $table->string('ultimo_nome')->nullable();
            $table->date('data_nascimento')->nullable();
            $table->string('cpf')->nullable();
            $table->string('telefone')->nullable();
            $table->string('whatsapp')->nullable();
            $table->string('email')->nullable();
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
