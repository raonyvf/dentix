<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\ClinicaProfissional;
use App\Models\HorarioProfissional;
use App\Models\Horario;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class ProfessionalController extends Controller
{
    public function index()
    {
        $users = User::whereDoesntHave('patient')
            ->where('id', '!=', auth()->id())
            ->get();

        return view('admin.profissionais.index', compact('users'));
    }

    public function show(User $profissional)
    {
        $profissional->load(['clinicasProfissional.clinic', 'horariosProfissional']);
        return view('admin.profissionais.show', compact('profissional'));
    }

    public function create()
    {
        $clinics = auth()->user()->organization->clinics ?? [];
        return view('admin.professionals.create', compact('clinics'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'first_name' => 'required',
            'middle_name' => 'nullable',
            'last_name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'nullable|string|min:8|confirmed',
            'phone' => 'nullable',
            'logradouro' => 'nullable',
            'numero' => 'nullable',
            'complemento' => 'nullable',
            'bairro' => 'nullable',
            'cep' => 'nullable',
            'cidade' => 'nullable',
            'estado' => 'nullable',
            'cpf' => 'nullable',
            'dentista' => 'nullable|boolean',
            'cro' => 'required_if:dentista,1|nullable',
            'cargo' => 'nullable',
            'especialidade' => 'nullable',
            'photo' => 'nullable|image',
            'clinicas' => 'nullable|array',
            'clinicas.*.selected' => 'nullable|boolean',
            'clinicas.*.comissao' => 'nullable|numeric',
            'clinicas.*.status' => 'nullable|in:Ativo,Inativo',
            'horarios' => 'nullable|array',
            'horarios.*.*.ativo' => 'nullable|boolean',
            'horarios.*.*.hora_inicio' => 'nullable|date_format:H:i',
            'horarios.*.*.hora_fim' => 'nullable|date_format:H:i',
            'schedule' => 'nullable|array',
            'document' => 'nullable|file',
            'contract_file' => 'nullable|file',
            'contract_start' => 'nullable|date',
            'contract_end' => 'nullable|date|after_or_equal:contract_start',
        ]);

        $password = $data['password'] ?? Str::random(10);

        $user = new User();
        $user->first_name = $data['first_name'];
        $user->middle_name = $data['middle_name'] ?? null;
        $user->last_name = $data['last_name'];
        $user->name = trim($data['first_name'] . ' ' . ($data['middle_name'] ?? '') . ' ' . $data['last_name']);
        $user->email = $data['email'];
        $user->phone = $data['phone'] ?? null;
        $user->logradouro = $data['logradouro'] ?? null;
        $user->numero = $data['numero'] ?? null;
        $user->complemento = $data['complemento'] ?? null;
        $user->bairro = $data['bairro'] ?? null;
        $user->cep = $data['cep'] ?? null;
        $user->cidade = $data['cidade'] ?? null;
        $user->estado = $data['estado'] ?? null;
        $user->cpf = $data['cpf'] ?? null;
        $user->dentista = $data['dentista'] ?? false;
        $user->cro = $data['cro'] ?? null;
        $user->cargo = $data['cargo'] ?? null;
        $user->especialidade = $data['especialidade'] ?? null;
        $user->organization_id = auth()->user()->organization_id;
        $user->password = Hash::make($password);
        $user->must_change_password = true;

        if ($request->hasFile('photo')) {
            $user->photo_path = $request->file('photo')->store('professionals', 'public');
        }

        if ($request->hasFile('document')) {
            $request->file('document')->store('professionals/documents', 'public');
        }

        if ($request->hasFile('contract_file')) {
            $request->file('contract_file')->store('professionals/contracts', 'public');
        }

        $user->save();


        if (!empty($data['clinicas'])) {
            foreach ($data['clinicas'] as $clinicId => $info) {
                if (!empty($info['selected'])) {
                    ClinicaProfissional::create([
                        'clinica_id' => $clinicId,
                        'profissional_id' => $user->id,
                        'status' => $info['status'] ?? 'Ativo',
                        'comissao' => $info['comissao'] ?? null,
                    ]);
                }
            }
        }

        if (!empty($data['horarios'])) {
            foreach ($data['horarios'] as $clinicId => $dias) {
                foreach ($dias as $dia => $hor) {
                    if (!empty($hor['ativo']) && ($hor['hora_inicio'] ?? false) && ($hor['hora_fim'] ?? false)) {
                        if ($hor['hora_fim'] <= $hor['hora_inicio']) {
                            return back()->withErrors('Horário final deve ser maior que o inicial.')->withInput();
                        }

                        $clinicHorario = Horario::where('clinic_id', $clinicId)
                            ->where('dia_semana', $dia)
                            ->first();

                        if ($clinicHorario) {
                            $inicio = Carbon::parse($hor['hora_inicio']);
                            $fim = Carbon::parse($hor['hora_fim']);
                            $abertura = Carbon::parse($clinicHorario->hora_inicio);
                            $fechamento = Carbon::parse($clinicHorario->hora_fim);

                            if ($inicio->lt($abertura) || $fim->gt($fechamento)) {
                                return back()->withErrors('Horário de trabalho fora do horário de funcionamento da clínica.')->withInput();
                            }
                        }

                        HorarioProfissional::create([
                            'clinica_id' => $clinicId,
                            'profissional_id' => $user->id,
                            'dia_semana' => $dia,
                            'hora_inicio' => $hor['hora_inicio'],
                            'hora_fim' => $hor['hora_fim'],
                        ]);
                    }
                }
            }
        }

        return redirect()->route('profissionais.index')->with('success', 'Profissional salvo com sucesso.');
    }

    public function edit(User $profissional)
    {
        $clinics = auth()->user()->organization->clinics ?? [];
        return view('admin.professionals.edit', compact('profissional', 'clinics'));
    }

    public function update(Request $request, User $profissional)
    {
        $data = $request->validate([
            'first_name' => 'required',
            'middle_name' => 'nullable',
            'last_name' => 'required',
            'email' => 'required|email|unique:users,email,' . $profissional->id,
            'phone' => 'nullable',
            'password' => 'nullable|string|min:8|confirmed',
            'logradouro' => 'nullable',
            'numero' => 'nullable',
            'complemento' => 'nullable',
            'bairro' => 'nullable',
            'cep' => 'nullable',
            'cidade' => 'nullable',
            'estado' => 'nullable',
            'cpf' => 'nullable',
            'dentista' => 'nullable|boolean',
            'cro' => 'required_if:dentista,1|nullable',
            'cargo' => 'nullable',
            'especialidade' => 'nullable',
            'photo' => 'nullable|image',
            'clinicas' => 'nullable|array',
            'clinicas.*.selected' => 'nullable|boolean',
            'clinicas.*.comissao' => 'nullable|numeric',
            'clinicas.*.status' => 'nullable|in:Ativo,Inativo',
            'horarios' => 'nullable|array',
            'horarios.*.*.ativo' => 'nullable|boolean',
            'horarios.*.*.hora_inicio' => 'nullable|date_format:H:i',
            'horarios.*.*.hora_fim' => 'nullable|date_format:H:i',
            'schedule' => 'nullable|array',
            'document' => 'nullable|file',
            'contract_file' => 'nullable|file',
            'contract_start' => 'nullable|date',
            'contract_end' => 'nullable|date|after_or_equal:contract_start',
        ]);

        $profissional->first_name = $data['first_name'];
        $profissional->middle_name = $data['middle_name'] ?? null;
        $profissional->last_name = $data['last_name'];
        $profissional->name = trim($data['first_name'] . ' ' . ($data['middle_name'] ?? '') . ' ' . $data['last_name']);
        $profissional->email = $data['email'];
        $profissional->phone = $data['phone'] ?? null;
        $profissional->logradouro = $data['logradouro'] ?? null;
        $profissional->numero = $data['numero'] ?? null;
        $profissional->complemento = $data['complemento'] ?? null;
        $profissional->bairro = $data['bairro'] ?? null;
        $profissional->cep = $data['cep'] ?? null;
        $profissional->cidade = $data['cidade'] ?? null;
        $profissional->estado = $data['estado'] ?? null;
        $profissional->cpf = $data['cpf'] ?? null;

        $profissional->dentista = $request->boolean('dentista');
        $profissional->cro = $data['cro'] ?? null;
        $profissional->cargo = $data['cargo'] ?? null;
        $profissional->especialidade = $data['especialidade'] ?? null;

        if ($request->filled('password')) {
            $profissional->password = Hash::make($data['password']);
            $profissional->must_change_password = true;
        }

        if ($request->hasFile('photo')) {
            $profissional->photo_path = $request->file('photo')->store('professionals', 'public');
        }

        if ($request->hasFile('document')) {
            $request->file('document')->store('professionals/documents', 'public');
        }

        if ($request->hasFile('contract_file')) {
            $request->file('contract_file')->store('professionals/contracts', 'public');
        }

        $profissional->save();


        ClinicaProfissional::where('profissional_id', $profissional->id)->delete();
        HorarioProfissional::where('profissional_id', $profissional->id)->delete();

        if (!empty($data['clinicas'])) {
            foreach ($data['clinicas'] as $clinicId => $info) {
                if (!empty($info['selected'])) {
                    ClinicaProfissional::create([
                        'clinica_id' => $clinicId,
                        'profissional_id' => $profissional->id,
                        'status' => $info['status'] ?? 'Ativo',
                        'comissao' => $info['comissao'] ?? null,
                    ]);
                }
            }
        }

        if (!empty($data['horarios'])) {
            foreach ($data['horarios'] as $clinicId => $dias) {
                foreach ($dias as $dia => $hor) {
                    if (!empty($hor['ativo']) && ($hor['hora_inicio'] ?? false) && ($hor['hora_fim'] ?? false)) {
                        if ($hor['hora_fim'] <= $hor['hora_inicio']) {
                            return back()->withErrors('Horário final deve ser maior que o inicial.')->withInput();
                        }

                        $clinicHorario = Horario::where('clinic_id', $clinicId)
                            ->where('dia_semana', $dia)
                            ->first();

                        if ($clinicHorario) {
                            $inicio = Carbon::parse($hor['hora_inicio']);
                            $fim = Carbon::parse($hor['hora_fim']);
                            $abertura = Carbon::parse($clinicHorario->hora_inicio);
                            $fechamento = Carbon::parse($clinicHorario->hora_fim);

                            if ($inicio->lt($abertura) || $fim->gt($fechamento)) {
                                return back()->withErrors('Horário de trabalho fora do horário de funcionamento da clínica.')->withInput();
                            }
                        }

                        HorarioProfissional::create([
                            'clinica_id' => $clinicId,
                            'profissional_id' => $profissional->id,
                            'dia_semana' => $dia,
                            'hora_inicio' => $hor['hora_inicio'],
                            'hora_fim' => $hor['hora_fim'],
                        ]);
                    }
                }
            }
        }

        return redirect()->route('profissionais.index')->with('success', 'Profissional atualizado com sucesso.');
    }

    public function destroy(User $profissional)
    {
        $profissional->delete();

        return redirect()->route('profissionais.index')
            ->with('success', 'Profissional removido com sucesso.');
    }
}
