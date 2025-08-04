<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Pessoa;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('patients', function (Blueprint $table) {
            $table->foreignId('pessoa_id')->nullable()->after('organization_id')->constrained('pessoas');
        });

        // move data
        if (Schema::hasTable('patients')) {
            $patients = DB::table('patients')->get();
            foreach ($patients as $patient) {
                if (empty($patient->first_name) || empty($patient->last_name)) {
                    continue;
                }

                $pessoa = Pessoa::create([
                    'organization_id' => $patient->organization_id ?? null,
                    'first_name' => $patient->first_name ?? null,
                    'middle_name' => $patient->middle_name ?? null,
                    'last_name' => $patient->last_name ?? null,
                    'data_nascimento' => $patient->data_nascimento ?? null,
                    'cpf' => $patient->cpf ?? null,
                    'phone' => $patient->telefone ?? null,
                    'whatsapp' => $patient->whatsapp ?? null,
                    'email' => $patient->email ?? null,
                    'cep' => $patient->cep ?? null,
                    'logradouro' => $patient->logradouro ?? null,
                    'numero' => $patient->numero ?? null,
                    'complemento' => $patient->complemento ?? null,
                    'bairro' => $patient->bairro ?? null,
                    'cidade' => $patient->cidade ?? null,
                    'estado' => $patient->estado ?? null,
                ]);

                DB::table('patients')
                    ->where('id', $patient->id)
                    ->update(['pessoa_id' => $pessoa->id]);
            }
        }

        $columns = [
            'first_name', 'middle_name', 'last_name', 'data_nascimento', 'cpf', 'telefone',
            'whatsapp', 'email', 'cep', 'logradouro', 'numero', 'complemento',
            'bairro', 'cidade', 'estado',
        ];
        $existingColumns = array_filter($columns, fn ($col) => Schema::hasColumn('patients', $col));

        if (!empty($existingColumns)) {
            Schema::table('patients', function (Blueprint $table) use ($existingColumns) {
                $table->dropColumn($existingColumns);
            });
        }
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
