<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Profissional;
use App\Models\ProfissionalHorario;
use App\Models\Person;
use App\Models\User;
use App\Models\Clinic;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Rules\Cpf;
use App\Rules\Cnpj;

class ProfessionalController extends Controller
{
    public function index()
    {
        $profissionais = Profissional::with(['user.person', 'clinics'])->get();

        $clinicas = Clinic::all();

        $totalProfissionais = $profissionais->count();
        $dentistas = $profissionais->where('cargo', 'Dentista')->count();
        $auxiliares = $profissionais->where('cargo', 'Auxiliar')->count();

        return view('profissionais.index', compact(
            'profissionais',
            'clinicas',
            'totalProfissionais',
            'dentistas',
            'auxiliares'
        ));
    }

    public function create()
    {
        $clinics = Clinic::where('organization_id', auth()->user()->organization_id)
            ->with('horarios')
            ->get();

        return view('profissionais.create', compact('clinics'));
    }

    public function show(Profissional $profissional)
    {
        $clinics = Clinic::where('organization_id', auth()->user()->organization_id)
            ->with('horarios')
            ->get();

        $horarios = $profissional->horariosTrabalho
            ->groupBy('clinic_id')
            ->map(function ($items) {
                return $items->mapWithKeys(fn($h) => [
                    $h->dia_semana => [
                        'inicio' => $h->hora_inicio,
                        'fim' => $h->hora_fim,
                    ],
                ]);
            })->toArray();

        return view('profissionais.show', compact('profissional', 'clinics', 'horarios'));
    }

    public function store(Request $request)
    {
        $data = $this->validateData($request);

        $personData = $this->extractPersonData($data);
        if ($request->hasFile('foto')) {
            $personData['photo_path'] = $request->file('foto')->store('profissionais', 'public');
        }
        $person = Person::create(array_merge([
            'organization_id' => auth()->user()->organization_id
        ], $personData));

        $user = null;
        if ($person->email) {
            $user = User::firstWhere('email', $person->email);
            if (!$user) {
                $user = User::create([
                    'email' => $person->email,
                    'organization_id' => auth()->user()->organization_id,
                    'password' => Hash::make(Str::random(8)),
                    'must_change_password' => true,
                    'person_id' => $person->id,
                ]);
            } else {
                $user->update(['person_id' => $person->id]);
            }
        }


        $profissional = Profissional::create(array_merge([
            'organization_id' => auth()->user()->organization_id,
            'person_id' => $person->id,
            'user_id' => $user?->id,
        ], $this->extractProfessionalData($data)));

        $profissional->clinics()->sync($request->input('clinics', []));

        $this->saveWorkSchedules($profissional, $request->input('horarios_trabalho', []));

        return redirect()->route('profissionais.index')->with('success', 'Profissional salvo com sucesso.');
    }

    public function edit(Profissional $profissional)
    {
        $clinics = Clinic::where('organization_id', auth()->user()->organization_id)
            ->with('horarios')
            ->get();

        $horarios = $profissional->horariosTrabalho
            ->groupBy('clinic_id')
            ->map(function ($items) {
                return $items->mapWithKeys(fn($h) => [
                    $h->dia_semana => [
                        'inicio' => $h->hora_inicio,
                        'fim' => $h->hora_fim,
                    ],
                ]);
            })->toArray();

        return view('profissionais.edit', compact('profissional', 'clinics', 'horarios'));
    }

    public function update(Request $request, Profissional $profissional)
    {
        $data = $this->validateData($request);

        $personData = $this->extractPersonData($data);
        if ($request->hasFile('foto')) {
            $personData['photo_path'] = $request->file('foto')->store('profissionais', 'public');
        }

        $profissional->person->update($personData);

        $profissional->update($this->extractProfessionalData($data));
        $profissional->clinics()->sync($request->input('clinics', []));
        $this->saveWorkSchedules($profissional, $request->input('horarios_trabalho', []), true);
        return redirect()->route('profissionais.index')->with('success', 'Profissional atualizado com sucesso.');
    }

    public function destroy(Profissional $profissional)
    {
        $profissional->delete();
        return redirect()->route('profissionais.index')->with('success', 'Profissional removido com sucesso.');
    }

    private function validateData(Request $request): array
    {
        $rules = [
            'first_name' => 'required',
            'middle_name' => 'required',
            'last_name' => 'required',
            'data_nascimento' => 'required|date',
            'sexo' => 'nullable',
            'naturalidade' => 'nullable',
            'nacionalidade' => 'nullable',
            'cpf' => ['required', new \App\Rules\Cpf],
            'rg' => 'nullable',
            'email' => 'nullable|email',
            'telefone' => 'nullable',
            'cep' => 'nullable',
            'logradouro' => 'nullable',
            'numero' => 'nullable',
            'complemento' => 'nullable',
            'bairro' => 'nullable',
            'cidade' => 'nullable',
            'estado' => 'nullable',
            'foto' => 'nullable|image',
            'numero_funcionario' => 'nullable',
            'email_corporativo' => 'nullable|email',
            'data_admissao' => 'required|date',
            'data_demissao' => 'nullable|date',
            'tipo_contrato' => 'nullable',
            'data_inicio_contrato' => 'required|date',
            'data_fim_contrato' => 'nullable|date',
            'total_horas_semanais' => 'nullable|integer',
            'regime_trabalho' => 'required',
            'funcao' => 'required',
            'cargo' => 'required',
            'cro' => 'nullable|numeric',
            'cro_uf' => 'nullable',
            'salario_fixo' => ['nullable', 'regex:/^\d+(,\d{2})?$/'],
            'salario_periodo' => 'nullable',
            'conta' => 'array',
            'conta.nome_banco' => 'nullable',
            'conta.tipo' => 'nullable',
            'conta.agencia' => 'nullable',
            'conta.numero' => 'nullable',
            'conta.cpf_cnpj_tipo' => 'required|in:cpf,cnpj',
            'conta.cpf_cnpj' => ['required'],
            'chave_pix' => 'nullable',
            'horarios_trabalho' => 'array',
            'comissoes' => 'array',
            'comissoes.*.comissao' => 'nullable|digits_between:1,2',
            'comissoes.*.protese' => 'nullable|digits_between:1,2',
            'clinics' => 'array',
            'clinics.*' => 'exists:clinics,id',
        ];

        $tipoConta = $request->input('conta.cpf_cnpj_tipo');
        if ($tipoConta === 'cpf') {
            $rules['conta.cpf_cnpj'][] = new \App\Rules\Cpf;
        } elseif ($tipoConta === 'cnpj') {
            $rules['conta.cpf_cnpj'][] = new \App\Rules\Cnpj;
        }

        $validator = Validator::make($request->all(), $rules);

        $clinics = Clinic::where('organization_id', auth()->user()->organization_id)
            ->with('horarios')
            ->get()
            ->keyBy('id');

        $validator->after(function ($validator) use ($clinics, $request) {
            foreach ($request->input('horarios_trabalho', []) as $clinicId => $dias) {
                $clinic = $clinics->get($clinicId);
                if (!$clinic) {
                    continue;
                }
                foreach ($dias as $dia => $horario) {
                    $inicio = $horario['inicio'] ?? null;
                    $fim = $horario['fim'] ?? null;
                    if (!$inicio && !$fim) {
                        continue;
                    }
                    $ref = $clinic->horarios->firstWhere('dia_semana', $dia);
                    if (!$ref) {
                        $validator->errors()->add("horarios_trabalho.$clinicId.$dia.inicio", 'Fora do horário da clínica');
                        continue;
                    }
                    if ($inicio && $inicio < $ref->hora_inicio) {
                        $validator->errors()->add("horarios_trabalho.$clinicId.$dia.inicio", 'Início antes da abertura');
                    }
                    if ($fim && $fim > $ref->hora_fim) {
                        $validator->errors()->add("horarios_trabalho.$clinicId.$dia.fim", 'Fim após o fechamento');
                    }
                }
            }
        });

        return $validator->validate();
    }

    private function extractPersonData(array $data): array
    {
        return [
            'first_name' => $data['first_name'],
            'middle_name' => $data['middle_name'] ?? null,
            'last_name' => $data['last_name'],
            'data_nascimento' => $data['data_nascimento'] ?? null,
            'sexo' => $data['sexo'] ?? null,
            'naturalidade' => $data['naturalidade'] ?? null,
            'nacionalidade' => $data['nacionalidade'] ?? null,
            'cpf' => $data['cpf'] ?? null,
            'rg' => $data['rg'] ?? null,
            'email' => $data['email'] ?? null,
            'phone' => $data['telefone'] ?? null,
            'cep' => $data['cep'] ?? null,
            'logradouro' => $data['logradouro'] ?? null,
            'numero' => $data['numero'] ?? null,
            'complemento' => $data['complemento'] ?? null,
            'bairro' => $data['bairro'] ?? null,
            'cidade' => $data['cidade'] ?? null,
            'estado' => $data['estado'] ?? null,
        ];
    }

    private function extractProfessionalData(array $data): array
    {
        return [
            'numero_funcionario' => $data['numero_funcionario'] ?? null,
            'email_corporativo' => $data['email_corporativo'] ?? null,
            'data_admissao' => $data['data_admissao'] ?? null,
            'data_demissao' => $data['data_demissao'] ?? null,
            'tipo_contrato' => $data['tipo_contrato'] ?? null,
            'data_inicio_contrato' => $data['data_inicio_contrato'] ?? null,
            'data_fim_contrato' => $data['data_fim_contrato'] ?? null,
            'total_horas_semanais' => $data['total_horas_semanais'] ?? null,
            'regime_trabalho' => $data['regime_trabalho'] ?? null,
            'funcao' => $data['funcao'] ?? null,
            'cargo' => $data['cargo'] ?? null,
            'cro' => $data['cro'] ?? null,
            'cro_uf' => $data['cro_uf'] ?? null,
            'salario_fixo' => isset($data['salario_fixo']) ? str_replace(',', '.', str_replace('.', '', $data['salario_fixo'])) : null,
            'salario_periodo' => $data['salario_periodo'] ?? null,
            'comissoes' => $data['comissoes'] ?? null,
            'conta' => $data['conta'] ?? null,
            'chave_pix' => $data['chave_pix'] ?? null,
        ];
    }

    private function saveWorkSchedules(Profissional $profissional, array $data, bool $replace = false): void
    {
        if ($replace) {
            $profissional->horariosTrabalho()->delete();
        }

        foreach ($data as $clinicId => $dias) {
            foreach ($dias as $dia => $horario) {
                if (($horario['inicio'] ?? false) && ($horario['fim'] ?? false)) {
                    $profissional->horariosTrabalho()->create([
                        'clinic_id' => $clinicId,
                        'dia_semana' => $dia,
                        'hora_inicio' => $horario['inicio'],
                        'hora_fim' => $horario['fim'],
                    ]);
                }
            }
        }
    }
}
