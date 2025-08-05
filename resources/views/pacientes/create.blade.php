@extends('layouts.app', ['hideErrors' => true])

@section('content')
@include('partials.breadcrumbs', ['crumbs' => [
    ['label' => 'Dashboard', 'url' => route('admin.index')],
    ['label' => 'Pacientes', 'url' => route('pacientes.index')],
    ['label' => 'Novo']
]])
<div class="w-full bg-white p-6 rounded-lg shadow" x-data="{ menorIdade: '{{ old('menor_idade', 'Não') }}' }">
    <h1 class="text-xl font-semibold mb-4">Novo Paciente</h1>
    @if ($errors->any())
        <x-alert-error>
            <div>Por favor, preencha todos os campos obrigatórios (*).</div>
            <ul class="list-disc list-inside mt-2">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </x-alert-error>
    @endif
    <form method="POST" action="{{ route('pacientes.store') }}" class="space-y-6" novalidate>
        @csrf
        <div class="rounded-sm border border-stroke bg-gray-50 p-4">
            <h2 class="mb-4 text-sm font-medium text-gray-700">Informações Básicas</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="text-sm font-medium text-gray-700 mb-2 block">Nome <span class="text-red-500">*</span></label>
                    <input class="w-full rounded border-[1.5px] border-stroke bg-gray-2 py-3 px-5 text-sm text-black focus:border-primary focus:outline-none" type="text" name="primeiro_nome" value="{{ old('primeiro_nome') }}" required />
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-700 mb-2 block">Nome do meio</label>
                    <input class="w-full rounded border-[1.5px] border-stroke bg-gray-2 py-3 px-5 text-sm text-black focus:border-primary focus:outline-none" type="text" name="nome_meio" value="{{ old('nome_meio') }}" />
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-700 mb-2 block">Último nome <span class="text-red-500">*</span></label>
                    <input class="w-full rounded border-[1.5px] border-stroke bg-gray-2 py-3 px-5 text-sm text-black focus:border-primary focus:outline-none" type="text" name="ultimo_nome" value="{{ old('ultimo_nome') }}" required />
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-700 mb-2 block">Data de nascimento <span class="text-red-500">*</span></label>
                    <input class="datepicker w-full rounded border-[1.5px] border-stroke bg-gray-2 py-3 px-5 text-sm text-black focus:border-primary focus:outline-none" type="text" name="data_nascimento" value="{{ old('data_nascimento') }}" required readonly />
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-700 mb-2 block">Sexo <span class="text-red-500">*</span></label>
                    <select name="sexo" class="w-full rounded border-[1.5px] border-stroke bg-gray-2 py-3 px-5 text-sm text-black focus:border-primary focus:outline-none" required>
                        <option value="">Selecione</option>
                        <option value="Masculino" @selected(old('sexo')==='Masculino')>Masculino</option>
                        <option value="Feminino" @selected(old('sexo')==='Feminino')>Feminino</option>
                        <option value="Outro" @selected(old('sexo')==='Outro')>Outro</option>
                    </select>
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-700 mb-2 block">CPF <span x-show="menorIdade !== 'Sim'" x-cloak class="text-red-500">*</span></label>
                    <input class="w-full rounded border-[1.5px] border-stroke bg-gray-2 py-3 px-5 text-sm text-black focus:border-primary focus:outline-none" type="text" name="cpf" value="{{ old('cpf') }}" x-bind:required="menorIdade !== 'Sim'" />
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-700 mb-2 block">Menor de idade?</label>
                    <select name="menor_idade" x-model="menorIdade" class="w-full rounded border-[1.5px] border-stroke bg-gray-2 py-3 px-5 text-sm text-black focus:border-primary focus:outline-none">
                        <option value="Não">Não</option>
                        <option value="Sim">Sim</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="rounded-sm border border-stroke bg-gray-50 p-4" x-show="menorIdade === 'Sim'" x-cloak>
            <h2 class="mb-4 text-sm font-medium text-gray-700">Dados do Responsável</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="text-sm font-medium text-gray-700 mb-2 block">Nome do responsável <span x-show="menorIdade === 'Sim'" x-cloak class="text-red-500">*</span></label>
                    <input class="w-full rounded border-[1.5px] border-stroke bg-gray-2 py-3 px-5 text-sm text-black focus:border-primary focus:outline-none" type="text" name="responsavel_first_name" value="{{ old('responsavel_first_name') }}" x-bind:required="menorIdade === 'Sim'" />
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-700 mb-2 block">Nome do meio</label>
                    <input class="w-full rounded border-[1.5px] border-stroke bg-gray-2 py-3 px-5 text-sm text-black focus:border-primary focus:outline-none" type="text" name="responsavel_middle_name" value="{{ old('responsavel_middle_name') }}" />
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-700 mb-2 block">Último nome <span x-show="menorIdade === 'Sim'" x-cloak class="text-red-500">*</span></label>
                    <input class="w-full rounded border-[1.5px] border-stroke bg-gray-2 py-3 px-5 text-sm text-black focus:border-primary focus:outline-none" type="text" name="responsavel_last_name" value="{{ old('responsavel_last_name') }}" x-bind:required="menorIdade === 'Sim'" />
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-700 mb-2 block">CPF do responsável <span x-show="menorIdade === 'Sim'" x-cloak class="text-red-500">*</span></label>
                    <input class="w-full rounded border-[1.5px] border-stroke bg-gray-2 py-3 px-5 text-sm text-black focus:border-primary focus:outline-none" type="text" name="responsavel_cpf" value="{{ old('responsavel_cpf') }}" x-bind:required="menorIdade === 'Sim'" />
                </div>
            </div>
        </div>
        <div class="rounded-sm border border-stroke bg-gray-50 p-4">
            <h2 class="mb-4 text-sm font-medium text-gray-700">Contato</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="text-sm font-medium text-gray-700 mb-2 block">Telefone <span class="text-red-500">*</span></label>
                    <input class="w-full rounded border-[1.5px] border-stroke bg-gray-2 py-3 px-5 text-sm text-black focus:border-primary focus:outline-none" type="text" name="telefone" value="{{ old('telefone') }}" required />
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-700 mb-2 block">Whatsapp</label>
                    <input class="w-full rounded border-[1.5px] border-stroke bg-gray-2 py-3 px-5 text-sm text-black focus:border-primary focus:outline-none" type="text" name="whatsapp" value="{{ old('whatsapp') }}" />
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-700 mb-2 block">E-mail</label>
                    <input class="w-full rounded border-[1.5px] border-stroke bg-gray-2 py-3 px-5 text-sm text-black focus:border-primary focus:outline-none" type="email" name="email" value="{{ old('email') }}" />
                </div>
            </div>
        </div>
        <div class="rounded-sm border border-stroke bg-gray-50 p-4">
            <h2 class="mb-4 text-sm font-medium text-gray-700">Endereço</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="text-sm font-medium text-gray-700 mb-2 block">CEP <span class="text-red-500">*</span></label>
                    <input class="w-full rounded border-[1.5px] border-stroke bg-gray-2 py-3 px-5 text-sm text-black focus:border-primary focus:outline-none" type="text" name="cep" value="{{ old('cep') }}" required />
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-700 mb-2 block">Logradouro <span class="text-red-500">*</span></label>
                    <input class="w-full rounded border-[1.5px] border-stroke bg-gray-2 py-3 px-5 text-sm text-black focus:border-primary focus:outline-none" type="text" name="logradouro" value="{{ old('logradouro') }}" required />
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-700 mb-2 block">Número <span class="text-red-500">*</span></label>
                    <input class="w-full rounded border-[1.5px] border-stroke bg-gray-2 py-3 px-5 text-sm text-black focus:border-primary focus:outline-none" type="text" name="numero" value="{{ old('numero') }}" required />
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-700 mb-2 block">Complemento</label>
                    <input class="w-full rounded border-[1.5px] border-stroke bg-gray-2 py-3 px-5 text-sm text-black focus:border-primary focus:outline-none" type="text" name="complemento" value="{{ old('complemento') }}" />
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-700 mb-2 block">Bairro <span class="text-red-500">*</span></label>
                    <input class="w-full rounded border-[1.5px] border-stroke bg-gray-2 py-3 px-5 text-sm text-black focus:border-primary focus:outline-none" type="text" name="bairro" value="{{ old('bairro') }}" required />
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-700 mb-2 block">Cidade <span class="text-red-500">*</span></label>
                    <input class="w-full rounded border-[1.5px] border-stroke bg-gray-2 py-3 px-5 text-sm text-black focus:border-primary focus:outline-none" type="text" name="cidade" value="{{ old('cidade') }}" required />
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-700 mb-2 block">Estado <span class="text-red-500">*</span></label>
                    <input class="w-full rounded border-[1.5px] border-stroke bg-gray-2 py-3 px-5 text-sm text-black focus:border-primary focus:outline-none" type="text" name="estado" value="{{ old('estado') }}" required />
                </div>
            </div>
        </div>
        <div class="rounded-sm border border-stroke bg-gray-50 p-4">
            <h2 class="mb-4 text-sm font-medium text-gray-700">Portal do Paciente</h2>
            <label class="inline-flex items-center space-x-2">
                <input type="checkbox" name="create_user" value="1" class="rounded border-stroke">
                <span class="text-sm text-gray-700">Criar usuário para acesso</span>
            </label>
        </div>
        <div class="flex justify-between pt-4">
            <a href="{{ route('pacientes.index') }}" class="py-2 px-4 rounded border border-stroke text-gray-700">Cancelar</a>
            <button type="submit" class="py-2 px-4 bg-blue-600 text-white rounded hover:bg-blue-700">Salvar Paciente</button>
        </div>
    </form>
</div>
@endsection
