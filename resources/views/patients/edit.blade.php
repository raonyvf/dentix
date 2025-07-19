@extends('layouts.app')

@section('content')
@include('partials.breadcrumbs', ['crumbs' => [
    ['label' => 'Dashboard', 'url' => route('admin.index')],
    ['label' => 'Pacientes', 'url' => route('pacientes.index')],
    ['label' => 'Editar']
]])
@php
    $primeiroNome = $paciente->first_name ?? '';
    $nomeMeio = $paciente->middle_name ?? '';
    $ultimoNome = $paciente->last_name ?? '';

    $respPrimeiro = $paciente->responsavel_first_name ?? '';
    $respMeio = $paciente->responsavel_middle_name ?? '';
    $respUltimo = $paciente->responsavel_last_name ?? '';
@endphp
<div x-data="{ menor: {{ old('menor_idade', $paciente->menor_idade) }}, tab: 'dados' }" class="w-full bg-white p-6 rounded-lg shadow">
    <h1 class="text-xl font-semibold mb-4">Editar Paciente</h1>
    <nav class="mb-4">
        <ul class="flex border-b">
            <li class="mr-1">
                <button type="button" @click="tab = 'dados'" :class="tab === 'dados' ? 'border-b-2 border-primary text-primary px-3 py-2' : 'px-3 py-2'">Dados</button>
            </li>
            <li class="mr-1">
                <button type="button" @click="tab = 'anamnese'" :class="tab === 'anamnese' ? 'border-b-2 border-primary text-primary px-3 py-2' : 'px-3 py-2'">Anamnese</button>
            </li>
            <li class="mr-1">
                <button type="button" @click="tab = 'odontograma'" :class="tab === 'odontograma' ? 'border-b-2 border-primary text-primary px-3 py-2' : 'px-3 py-2'">Odontograma</button>
            </li>
            <li>
                <button type="button" @click="tab = 'documentos'" :class="tab === 'documentos' ? 'border-b-2 border-primary text-primary px-3 py-2' : 'px-3 py-2'">Documentos</button>
            </li>
        </ul>
    </nav>
    @if ($errors->any())
        <div class="mb-4">
            <ul class="list-disc list-inside text-sm text-red-600">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <div x-show="tab === 'dados'">
    <form method="POST" action="{{ route('pacientes.update', $paciente) }}" class="space-y-6">
        @csrf
        @method('PUT')

        <div>
            <div class="rounded-sm border border-stroke bg-gray-50 p-4 mb-4">
                <h2 class="mb-4 text-sm font-medium text-gray-700">Informações Básicas</h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="mb-2 block text-sm font-medium text-gray-700">Nome</label>
                        <input class="w-full rounded border-[1.5px] border-stroke bg-gray-2 py-3 px-5 text-sm text-black focus:border-primary focus:outline-none" type="text" name="primeiro_nome" value="{{ old('primeiro_nome', $primeiroNome) }}" required />
                    </div>
                    <div>
                        <label class="mb-2 block text-sm font-medium text-gray-700">Nome do meio</label>
                        <input class="w-full rounded border-[1.5px] border-stroke bg-gray-2 py-3 px-5 text-sm text-black focus:border-primary focus:outline-none" type="text" name="nome_meio" value="{{ old('nome_meio', $nomeMeio) }}" />
                    </div>
                    <div>
                        <label class="mb-2 block text-sm font-medium text-gray-700">Último nome</label>
                        <input class="w-full rounded border-[1.5px] border-stroke bg-gray-2 py-3 px-5 text-sm text-black focus:border-primary focus:outline-none" type="text" name="ultimo_nome" value="{{ old('ultimo_nome', $ultimoNome) }}" required />
                    </div>
                    <div>
                        <label class="mb-2 block text-sm font-medium text-gray-700">Data de nascimento</label>
                        <input class="w-full rounded border-[1.5px] border-stroke bg-gray-2 py-3 px-5 text-sm text-black focus:border-primary focus:outline-none" type="date" name="data_nascimento" value="{{ old('data_nascimento', optional($paciente->data_nascimento)->format('Y-m-d')) }}" />
                    </div>
                    <div>
                        <label class="mb-2 block text-sm font-medium text-gray-700">CPF</label>
                        <input type="text" name="cpf" value="{{ old('cpf', $paciente->cpf) }}" x-bind:required="menor != 1" class="w-full rounded border-[1.5px] border-stroke bg-gray-2 py-3 px-5 text-sm text-black focus:border-primary focus:outline-none" />
                    </div>
                    <div>
                        <label class="mb-2 block text-sm font-medium text-gray-700">Menor de idade?</label>
                        <select name="menor_idade" x-model="menor" class="w-full rounded border-[1.5px] border-stroke bg-gray-2 py-3 px-5 text-sm text-black focus:border-primary focus:outline-none">
                            <option value="0">Não</option>
                            <option value="1">Sim</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="rounded-sm border border-stroke bg-gray-50 p-4 mb-4" x-show="menor == 1" x-cloak>
                <h2 class="mb-4 text-sm font-medium text-gray-700">Responsável</h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="mb-2 block text-sm font-medium text-gray-700">Nome</label>
                        <input type="text" name="responsavel_primeiro_nome" value="{{ old('responsavel_primeiro_nome', $respPrimeiro) }}" class="w-full rounded border-[1.5px] border-stroke bg-gray-2 py-3 px-5 text-sm text-black focus:border-primary focus:outline-none" />
                    </div>
                    <div>
                        <label class="mb-2 block text-sm font-medium text-gray-700">Nome do meio</label>
                        <input type="text" name="responsavel_nome_meio" value="{{ old('responsavel_nome_meio', $respMeio) }}" class="w-full rounded border-[1.5px] border-stroke bg-gray-2 py-3 px-5 text-sm text-black focus:border-primary focus:outline-none" />
                    </div>
                    <div>
                        <label class="mb-2 block text-sm font-medium text-gray-700">Último nome</label>
                        <input type="text" name="responsavel_ultimo_nome" value="{{ old('responsavel_ultimo_nome', $respUltimo) }}" class="w-full rounded border-[1.5px] border-stroke bg-gray-2 py-3 px-5 text-sm text-black focus:border-primary focus:outline-none" />
                    </div>
                    <div class="sm:col-span-2">
                        <label class="mb-2 block text-sm font-medium text-gray-700">CPF</label>
                        <input type="text" name="responsavel_cpf" value="{{ old('responsavel_cpf', $paciente->responsavel_cpf) }}" x-bind:required="menor == 1" class="w-full rounded border-[1.5px] border-stroke bg-gray-2 py-3 px-5 text-sm text-black focus:border-primary focus:outline-none" />
                    </div>
                </div>
            </div>

            <div class="rounded-sm border border-stroke bg-gray-50 p-4 mb-4">
                <h2 class="mb-4 text-sm font-medium text-gray-700">Contato</h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="mb-2 block text-sm font-medium text-gray-700">Telefone</label>
                        <input type="text" name="telefone" value="{{ old('telefone', $paciente->telefone) }}" class="w-full rounded border-[1.5px] border-stroke bg-gray-2 py-3 px-5 text-sm text-black focus:border-primary focus:outline-none" />
                    </div>
                    <div>
                        <label class="mb-2 block text-sm font-medium text-gray-700">E-mail</label>
                        <input type="email" name="email" value="{{ old('email', $paciente->email) }}" class="w-full rounded border-[1.5px] border-stroke bg-gray-2 py-3 px-5 text-sm text-black focus:border-primary focus:outline-none" />
                    </div>
                </div>
            </div>

            <div class="rounded-sm border border-stroke bg-gray-50 p-4">
                <h2 class="mb-4 text-sm font-medium text-gray-700">Endereço</h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="mb-2 block text-sm font-medium text-gray-700">CEP</label>
                        <input type="text" name="cep" value="{{ old('cep', $paciente->cep) }}" class="w-full rounded border-[1.5px] border-stroke bg-gray-2 py-3 px-5 text-sm text-black focus:border-primary focus:outline-none" />
                    </div>
                    <div class="sm:col-span-2">
                        <label class="mb-2 block text-sm font-medium text-gray-700">Rua</label>
                        <input type="text" name="endereco_rua" value="{{ old('endereco_rua', $paciente->endereco_rua) }}" class="w-full rounded border-[1.5px] border-stroke bg-gray-2 py-3 px-5 text-sm text-black focus:border-primary focus:outline-none" />
                    </div>
                    <div>
                        <label class="mb-2 block text-sm font-medium text-gray-700">Número</label>
                        <input type="text" name="numero" value="{{ old('numero', $paciente->numero) }}" class="w-full rounded border-[1.5px] border-stroke bg-gray-2 py-3 px-5 text-sm text-black focus:border-primary focus:outline-none" />
                    </div>
                    <div>
                        <label class="mb-2 block text-sm font-medium text-gray-700">Complemento</label>
                        <input type="text" name="complemento" value="{{ old('complemento', $paciente->complemento) }}" class="w-full rounded border-[1.5px] border-stroke bg-gray-2 py-3 px-5 text-sm text-black focus:border-primary focus:outline-none" />
                    </div>
                    <div>
                        <label class="mb-2 block text-sm font-medium text-gray-700">Bairro</label>
                        <input type="text" name="bairro" value="{{ old('bairro', $paciente->bairro) }}" class="w-full rounded border-[1.5px] border-stroke bg-gray-2 py-3 px-5 text-sm text-black focus:border-primary focus:outline-none" />
                    </div>
                    <div>
                        <label class="mb-2 block text-sm font-medium text-gray-700">Cidade</label>
                        <input type="text" name="cidade" value="{{ old('cidade', $paciente->cidade) }}" class="w-full rounded border-[1.5px] border-stroke bg-gray-2 py-3 px-5 text-sm text-black focus:border-primary focus:outline-none" />
                    </div>
                    <div>
                        <label class="mb-2 block text-sm font-medium text-gray-700">Estado</label>
                        <input type="text" name="estado" value="{{ old('estado', $paciente->estado) }}" class="w-full rounded border-[1.5px] border-stroke bg-gray-2 py-3 px-5 text-sm text-black focus:border-primary focus:outline-none" />
                    </div>
                </div>
            </div>
        </div>

        <div class="flex justify-end gap-2 pt-4">
            <a href="{{ route('pacientes.index') }}" class="py-2 px-4 rounded border border-stroke text-gray-700">Cancelar</a>
            <button type="submit" class="py-2 px-4 bg-blue-600 text-white rounded hover:bg-blue-700">Salvar</button>
        </div>
    </form>
    </div>
    <div x-show="tab === 'anamnese'" x-cloak>
        <h2 class="text-lg font-medium mb-4">Anamnese</h2>
        <p class="text-sm text-gray-600">Funcionalidade em desenvolvimento.</p>
    </div>
    <div x-show="tab === 'odontograma'" x-cloak>
        <h2 class="text-lg font-medium mb-4">Odontograma</h2>
        <p class="text-sm text-gray-600">Funcionalidade em desenvolvimento.</p>
    </div>
    <div x-show="tab === 'documentos'" x-cloak>
        <h2 class="text-lg font-medium mb-4">Documentos</h2>
        <p class="text-sm text-gray-600">Funcionalidade em desenvolvimento.</p>
    </div>
</div>
@endsection
