@extends('layouts.app')

@section('content')
@include('partials.breadcrumbs', ['crumbs' => [
    ['label' => 'Dashboard', 'url' => route('admin.index')],
    ['label' => 'Pacientes', 'url' => route('pacientes.index')],
    ['label' => 'Editar']
]])
<div class="w-full bg-white p-6 rounded-lg shadow" x-data="{ menorIdade: '{{ old('menor_idade', $paciente->menor_idade) }}' }">
    <h1 class="text-xl font-semibold mb-4">Editar Paciente</h1>
    @if ($errors->any())
        <div class="mb-4">
            <ul class="list-disc list-inside text-sm text-red-600">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <form method="POST" action="{{ route('pacientes.update', $paciente) }}" class="space-y-6">
        @csrf
        @method('PUT')
        <div class="rounded-sm border border-stroke bg-gray-50 p-4">
            <h2 class="mb-4 text-sm font-medium text-gray-700">Informações Básicas</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="text-sm font-medium text-gray-700 mb-2 block">Nome</label>
                    <input class="w-full rounded border-[1.5px] border-stroke bg-gray-2 py-3 px-5 text-sm text-black focus:border-primary focus:outline-none" type="text" name="nome" value="{{ old('nome', $paciente->nome) }}" required />
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-700 mb-2 block">Nome do meio</label>
                    <input class="w-full rounded border-[1.5px] border-stroke bg-gray-2 py-3 px-5 text-sm text-black focus:border-primary focus:outline-none" type="text" name="nome_meio" value="{{ old('nome_meio', $paciente->nome_meio) }}" />
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-700 mb-2 block">Último nome</label>
                    <input class="w-full rounded border-[1.5px] border-stroke bg-gray-2 py-3 px-5 text-sm text-black focus:border-primary focus:outline-none" type="text" name="ultimo_nome" value="{{ old('ultimo_nome', $paciente->ultimo_nome) }}" required />
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-700 mb-2 block">Data de nascimento</label>
                    <input class="w-full rounded border-[1.5px] border-stroke bg-gray-2 py-3 px-5 text-sm text-black focus:border-primary focus:outline-none" type="date" name="data_nascimento" value="{{ old('data_nascimento', $paciente->data_nascimento?->format('Y-m-d')) }}" required />
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-700 mb-2 block">CPF</label>
                    <input class="w-full rounded border-[1.5px] border-stroke bg-gray-2 py-3 px-5 text-sm text-black focus:border-primary focus:outline-none" type="text" name="cpf" value="{{ old('cpf', $paciente->cpf) }}" x-bind:required="menorIdade !== 'Sim'" />
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
                    <label class="text-sm font-medium text-gray-700 mb-2 block">Nome do responsável</label>
                    <input class="w-full rounded border-[1.5px] border-stroke bg-gray-2 py-3 px-5 text-sm text-black focus:border-primary focus:outline-none" type="text" name="responsavel_nome" value="{{ old('responsavel_nome', $paciente->responsavel_nome) }}" x-bind:required="menorIdade === 'Sim'" />
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-700 mb-2 block">Nome do meio</label>
                    <input class="w-full rounded border-[1.5px] border-stroke bg-gray-2 py-3 px-5 text-sm text-black focus:border-primary focus:outline-none" type="text" name="responsavel_nome_meio" value="{{ old('responsavel_nome_meio', $paciente->responsavel_nome_meio) }}" />
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-700 mb-2 block">Último nome</label>
                    <input class="w-full rounded border-[1.5px] border-stroke bg-gray-2 py-3 px-5 text-sm text-black focus:border-primary focus:outline-none" type="text" name="responsavel_ultimo_nome" value="{{ old('responsavel_ultimo_nome', $paciente->responsavel_ultimo_nome) }}" />
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-700 mb-2 block">CPF do responsável</label>
                    <input class="w-full rounded border-[1.5px] border-stroke bg-gray-2 py-3 px-5 text-sm text-black focus:border-primary focus:outline-none" type="text" name="responsavel_cpf" value="{{ old('responsavel_cpf', $paciente->responsavel_cpf) }}" x-bind:required="menorIdade === 'Sim'" />
                </div>
            </div>
        </div>
        <div class="rounded-sm border border-stroke bg-gray-50 p-4">
            <h2 class="mb-4 text-sm font-medium text-gray-700">Contato</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="text-sm font-medium text-gray-700 mb-2 block">Telefone</label>
                    <input class="w-full rounded border-[1.5px] border-stroke bg-gray-2 py-3 px-5 text-sm text-black focus:border-primary focus:outline-none" type="text" name="telefone" value="{{ old('telefone', $paciente->telefone) }}" />
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-700 mb-2 block">E-mail</label>
                    <input class="w-full rounded border-[1.5px] border-stroke bg-gray-2 py-3 px-5 text-sm text-black focus:border-primary focus:outline-none" type="email" name="email" value="{{ old('email', $paciente->email) }}" />
                </div>
            </div>
        </div>
        <div class="rounded-sm border border-stroke bg-gray-50 p-4">
            <h2 class="mb-4 text-sm font-medium text-gray-700">Endereço</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="text-sm font-medium text-gray-700 mb-2 block">CEP</label>
                    <input class="w-full rounded border-[1.5px] border-stroke bg-gray-2 py-3 px-5 text-sm text-black focus:border-primary focus:outline-none" type="text" name="cep" value="{{ old('cep', $paciente->cep) }}" />
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-700 mb-2 block">Rua</label>
                    <input class="w-full rounded border-[1.5px] border-stroke bg-gray-2 py-3 px-5 text-sm text-black focus:border-primary focus:outline-none" type="text" name="rua" value="{{ old('rua', $paciente->rua) }}" />
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-700 mb-2 block">Número</label>
                    <input class="w-full rounded border-[1.5px] border-stroke bg-gray-2 py-3 px-5 text-sm text-black focus:border-primary focus:outline-none" type="text" name="numero" value="{{ old('numero', $paciente->numero) }}" />
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-700 mb-2 block">Complemento</label>
                    <input class="w-full rounded border-[1.5px] border-stroke bg-gray-2 py-3 px-5 text-sm text-black focus:border-primary focus:outline-none" type="text" name="complemento" value="{{ old('complemento', $paciente->complemento) }}" />
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-700 mb-2 block">Bairro</label>
                    <input class="w-full rounded border-[1.5px] border-stroke bg-gray-2 py-3 px-5 text-sm text-black focus:border-primary focus:outline-none" type="text" name="bairro" value="{{ old('bairro', $paciente->bairro) }}" />
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-700 mb-2 block">Cidade</label>
                    <input class="w-full rounded border-[1.5px] border-stroke bg-gray-2 py-3 px-5 text-sm text-black focus:border-primary focus:outline-none" type="text" name="cidade" value="{{ old('cidade', $paciente->cidade) }}" />
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-700 mb-2 block">Estado</label>
                    <input class="w-full rounded border-[1.5px] border-stroke bg-gray-2 py-3 px-5 text-sm text-black focus:border-primary focus:outline-none" type="text" name="estado" value="{{ old('estado', $paciente->estado) }}" />
                </div>
            </div>
        </div>
        <div class="flex justify-between pt-4">
            <a href="{{ route('pacientes.index') }}" class="py-2 px-4 rounded border border-stroke text-gray-700">Cancelar</a>
            <button type="submit" class="py-2 px-4 bg-blue-600 text-white rounded hover:bg-blue-700">Salvar Paciente</button>
        </div>
    </form>
</div>
@endsection
