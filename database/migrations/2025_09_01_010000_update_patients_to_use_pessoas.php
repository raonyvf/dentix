<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Patient;
use App\Models\Pessoa;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('patients', function (Blueprint $table) {
            $table->foreignId('pessoa_id')->nullable()->after('organization_id')->constrained('pessoas');
        });

        // move data
        if (Schema::hasTable('patients')) {
            $patients = Patient::withoutGlobalScopes()->get();
            foreach ($patients as $patient) {
                $pessoa = Pessoa::create([
                    'organization_id' => $patient->organization_id,
                    'first_name' => $patient->first_name,
                    'middle_name' => $patient->middle_name,
                    'last_name' => $patient->last_name,
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
                $patient->pessoa_id = $pessoa->id;
                $patient->save();
            }
        }

        Schema::table('patients', function (Blueprint $table) {
            $table->dropColumn([
                'first_name','middle_name','last_name','data_nascimento','cpf','telefone','whatsapp','email','cep','logradouro','numero','complemento','bairro','cidade','estado'
            ]);
        });
    }

    public function down(): void
    {
        Schema::table('patients', function (Blueprint $table) {
            $table->string('first_name')->nullable();
            $table->string('middle_name')->nullable();
            $table->string('last_name')->nullable();
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
            $table->dropForeign(['pessoa_id']);
            $table->dropColumn('pessoa_id');
        });
    }
};
