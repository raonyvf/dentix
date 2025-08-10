<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Profissional;
use App\Models\ProfissionalHorario;
use App\Models\Pessoa;
use App\Models\Usuario;
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
        $profissionais = Profissional::with(['usuario.pessoa', 'clinics'])->get();

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
        $clinics = Clinic::where('organizacao_id', auth()->user()->organizacao_id)
            ->with('horarios')
            ->get();

        return view('profissionais.create', compact('clinics'));
    }

    public function show(Profissional $profissional)
    {
        $clinics = Clinic::where('organizacao_id', auth()->user()->organizacao_id)
            ->with('horarios')
            ->get();

        $horarios = $profissional->horariosTrabalho
            ->groupBy('clinica_id')
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

        $pessoaData = $this->extractPessoaData($data);
        if ($request->hasFile('foto')) {
            $pessoaData['photo_path'] = $request->file('foto')->store('profissionais', 'public');
        }
        $pessoa = Pessoa::create(array_merge([
            'organizacao_id' => auth()->user()->organizacao_id
        ], $pessoaData));

        $usuario = null;
        if ($pessoa->email) {
            $usuario = Usuario::firstWhere('email', $pessoa->email);
            if (!$usuario) {
                $usuario = Usuario::create([
                    'email' => $pessoa->email,
                    'organizacao_id' => auth()->user()->organizacao_id,
                    'password' => Hash::make(Str::random(8)),
                    'must_change_password' => true,
                    'pessoa_id' => $pessoa->id,
                ]);
            } else {
                $usuario->update(['pessoa_id' => $pessoa->id]);
            }
        }


        $profissional = Profissional::create(array_merge([
            'organizacao_id' => auth()->user()->organizacao_id,
            'pessoa_id' => $pessoa->id,
            'usuario_id' => $usuario?->id,
        ], $this->extractProfessionalData($data)));

        $profissional->clinics()->sync($request->input('clinics', []));

        $this->saveWorkSchedules($profissional, $request->input('horarios_trabalho', []));
        $this->saveCommissions($profissional, $request->input('comissoes', []));

        return redirect()->route('profissionais.index')->with('success', 'Profissional salvo com sucesso.');
    }

    public function edit(Profissional $profissional)
    {
        $clinics = Clinic::where('organizacao_id', auth()->user()->organizacao_id)
            ->with('horarios')
            ->get();

        $horarios = $profissional->horariosTrabalho
            ->groupBy('clinica_id')
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

        $pessoaData = $this->extractPessoaData($data);
        if ($request->hasFile('foto')) {
            $pessoaData['photo_path'] = $request->file('foto')->store('profissionais', 'public');
        }

        $profissional->pessoa->update($pessoaData);

        $profissional->update($this->extractProfessionalData($data));
        $profissional->clinics()->sync($request->input('clinics', []));
        $this->saveWorkSchedules($profissional, $request->input('horarios_trabalho', []), true);
        $this->saveCommissions($profissional, $request->input('comissoes', []), true);
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
            'primeiro_nome' => 'required',
            'nome_meio' => 'nullable',
            'ultimo_nome' => 'required',
            'data_nascimento' => 'required|date',
            'sexo' => 'required',
            'naturalidade' => 'required',
            'nacionalidade' => 'required',
            'cpf' => ['required', new \App\Rules\Cpf],
            'rg' => 'nullable',
            'email' => 'required|email',
            'telefone' => 'required',
            'cep' => 'required',
            'logradouro' => 'required',
            'numero' => 'required',
            'complemento' => 'nullable',
            'bairro' => 'required',
            'cidade' => 'required',
            'estado' => 'required',
            'foto' => 'nullable|image',
            'numero_funcionario' => 'nullable',
            'email_corporativo' => 'nullable|email',
            'tipo_contrato' => 'required',
            'data_inicio_contrato' => 'required|date',
            'data_fim_contrato' => 'nullable|date',
            'total_horas_semanais' => 'nullable|integer',
            'regime_trabalho' => 'required',
            'funcao' => 'required',
            'cargo' => 'required',
            'cro' => 'nullable|numeric',
            'cro_uf' => 'nullable',
            'salario_fixo' => ['required', 'regex:/^\d+(?:[,.]\d{2})?$/'],
            'salario_periodo' => 'nullable',
            'conta' => 'array',
            'conta.nome_banco' => 'nullable',
            'conta.tipo' => 'nullable',
            'conta.agencia' => 'nullable',
            'conta.numero' => 'nullable',
            'conta.cpf_cnpj_tipo' => 'nullable|in:cpf,cnpj',
            'conta.cpf_cnpj' => ['nullable'],
            'chave_pix' => 'nullable',
            'horarios_trabalho' => 'array',
            'comissoes' => 'array',
            'comissoes.*.comissao' => 'nullable|numeric',
            'comissoes.*.protese' => 'nullable|numeric',
            'clinics' => 'required|array|min:1',
            'clinics.*' => 'exists:clinicas,id',
        ];

        $requiresCpfCnpj = $request->filled('conta.nome_banco') &&
            $request->filled('conta.tipo') &&
            $request->filled('conta.agencia');
        if ($requiresCpfCnpj) {
            $rules['conta.cpf_cnpj_tipo'] = 'required|in:cpf,cnpj';
            $rules['conta.cpf_cnpj'] = ['required'];
        }

        if ($request->input('funcao') === 'Dentista') {
            $rules['cro'] = 'required|numeric';
        }

        $tipoConta = $request->input('conta.cpf_cnpj_tipo');
        if ($request->filled('conta.cpf_cnpj') && $tipoConta === 'cpf') {
            $rules['conta.cpf_cnpj'][] = new \App\Rules\Cpf;
        } elseif ($request->filled('conta.cpf_cnpj') && $tipoConta === 'cnpj') {
            $rules['conta.cpf_cnpj'][] = new \App\Rules\Cnpj;
        }

        $validator = Validator::make($request->all(), $rules);

        $clinics = Clinic::where('organizacao_id', auth()->user()->organizacao_id)
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

    private function extractPessoaData(array $data): array
    {
        return [
            'primeiro_nome' => $data['primeiro_nome'],
            'nome_meio' => $data['nome_meio'] ?? null,
            'ultimo_nome' => $data['ultimo_nome'],
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
            'tipo_contrato' => $data['tipo_contrato'] ?? null,
            'data_inicio_contrato' => $data['data_inicio_contrato'] ?? null,
            'data_fim_contrato' => $data['data_fim_contrato'] ?? null,
            'total_horas_semanais' => $data['total_horas_semanais'] ?? null,
            'regime_trabalho' => $data['regime_trabalho'] ?? null,
            'funcao' => $data['funcao'] ?? null,
            'cargo' => $data['cargo'] ?? null,
            'cro' => $data['cro'] ?? null,
            'cro_uf' => $data['cro_uf'] ?? null,
            'salario_fixo' => isset($data['salario_fixo'])
                ? str_replace(',', '.', preg_replace('/(?<=\\d)\.(?=\d{3}(?:\D|$))/', '', $data['salario_fixo']))
                : null,
            'salario_periodo' => $data['salario_periodo'] ?? null,
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
                        'clinica_id' => $clinicId,
                        'dia_semana' => $dia,
                        'hora_inicio' => $horario['inicio'],
                        'hora_fim' => $horario['fim'],
                    ]);
                }
            }
        }
    }

    private function saveCommissions(Profissional $profissional, array $data, bool $replace = false): void
    {
        if ($replace) {
            $profissional->comissoes()->delete();
        }

        foreach ($data as $clinicId => $vals) {
            if (($vals['comissao'] ?? null) !== null || ($vals['protese'] ?? null) !== null) {
                $profissional->comissoes()->create([
                    'clinica_id' => $clinicId,
                    'comissao' => $vals['comissao'] ?? null,
                    'protese' => $vals['protese'] ?? null,
                ]);
            }
        }
    }
}
