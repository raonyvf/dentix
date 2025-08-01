@extends('layouts.app', ['hideErrors' => true])

@section('content')
@include('partials.breadcrumbs', ['crumbs' => [
    ['label' => 'Dashboard', 'url' => route('admin.index')],
    ['label' => 'Pacientes', 'url' => route('pacientes.index')],
    ['label' => 'Editar']
]])
<div class="w-full bg-white p-6 rounded-lg shadow" x-data="{ activeTab: 'dados', menorIdade: '{{ old('menor_idade', $paciente->menor_idade ? 'Sim' : 'Não') }}' }">
    <h1 class="text-xl font-semibold mb-4">Editar Paciente</h1>
    <div class="border-b mb-6">
        <nav class="-mb-px flex space-x-4" aria-label="Tabs">
            <button type="button" @click="activeTab = 'dados'"
                :class="activeTab === 'dados' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                Dados pessoais
            </button>
            <button type="button" @click="activeTab = 'agendamentos'"
                :class="activeTab === 'agendamentos' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                Agendamentos
            </button>
            <button type="button" @click="activeTab = 'plano_tratamento'"
                :class="activeTab === 'plano_tratamento' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                Plano de Tratamento
            </button>
            <button type="button" @click="activeTab = 'documentos'"
                :class="activeTab === 'documentos' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                Documentos
            </button>
            <button type="button" @click="activeTab = 'financeiro'"
                :class="activeTab === 'financeiro' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                Financeiro
            </button>
        </nav>
    </div>
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
    <div x-show="activeTab === 'dados'">
    <form method="POST" action="{{ route('pacientes.update', $paciente) }}" class="space-y-6" novalidate>
        @csrf
        @method('PUT')
        <div class="rounded-sm border border-stroke bg-gray-50 p-4">
            <h2 class="mb-4 text-sm font-medium text-gray-700">Informações Básicas</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="text-sm font-medium text-gray-700 mb-2 block">Nome <span class="text-red-500">*</span></label>
                    <input class="w-full rounded border-[1.5px] border-stroke bg-gray-2 py-3 px-5 text-sm text-black focus:border-primary focus:outline-none" type="text" name="first_name" value="{{ old('first_name', $paciente->person->first_name) }}" required />
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-700 mb-2 block">Nome do meio</label>
                    <input class="w-full rounded border-[1.5px] border-stroke bg-gray-2 py-3 px-5 text-sm text-black focus:border-primary focus:outline-none" type="text" name="middle_name" value="{{ old('middle_name', $paciente->person->middle_name) }}" />
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-700 mb-2 block">Último nome <span class="text-red-500">*</span></label>
                    <input class="w-full rounded border-[1.5px] border-stroke bg-gray-2 py-3 px-5 text-sm text-black focus:border-primary focus:outline-none" type="text" name="last_name" value="{{ old('last_name', $paciente->person->last_name) }}" required />
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-700 mb-2 block">Data de nascimento <span class="text-red-500">*</span></label>
                    <input class="w-full rounded border-[1.5px] border-stroke bg-gray-2 py-3 px-5 text-sm text-black focus:border-primary focus:outline-none" type="date" name="data_nascimento" value="{{ old('data_nascimento', $paciente->person->data_nascimento?->format('Y-m-d')) }}" required />
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-700 mb-2 block">CPF <span x-show="menorIdade !== 'Sim'" x-cloak class="text-red-500">*</span></label>
                    <input class="w-full rounded border-[1.5px] border-stroke bg-gray-2 py-3 px-5 text-sm text-black focus:border-primary focus:outline-none" type="text" name="cpf" value="{{ old('cpf', $paciente->person->cpf) }}" x-bind:required="menorIdade !== 'Sim'" />
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
                    <input class="w-full rounded border-[1.5px] border-stroke bg-gray-2 py-3 px-5 text-sm text-black focus:border-primary focus:outline-none" type="text" name="responsavel_first_name" value="{{ old('responsavel_first_name', $paciente->responsavel_first_name) }}" x-bind:required="menorIdade === 'Sim'" />
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-700 mb-2 block">Nome do meio</label>
                    <input class="w-full rounded border-[1.5px] border-stroke bg-gray-2 py-3 px-5 text-sm text-black focus:border-primary focus:outline-none" type="text" name="responsavel_middle_name" value="{{ old('responsavel_middle_name', $paciente->responsavel_middle_name) }}" />
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-700 mb-2 block">Último nome</label>
                    <input class="w-full rounded border-[1.5px] border-stroke bg-gray-2 py-3 px-5 text-sm text-black focus:border-primary focus:outline-none" type="text" name="responsavel_last_name" value="{{ old('responsavel_last_name', $paciente->responsavel_last_name) }}" />
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-700 mb-2 block">CPF do responsável <span x-show="menorIdade === 'Sim'" x-cloak class="text-red-500">*</span></label>
                    <input class="w-full rounded border-[1.5px] border-stroke bg-gray-2 py-3 px-5 text-sm text-black focus:border-primary focus:outline-none" type="text" name="responsavel_cpf" value="{{ old('responsavel_cpf', $paciente->responsavel_cpf) }}" x-bind:required="menorIdade === 'Sim'" />
                </div>
            </div>
        </div>
        <div class="rounded-sm border border-stroke bg-gray-50 p-4">
            <h2 class="mb-4 text-sm font-medium text-gray-700">Contato</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="text-sm font-medium text-gray-700 mb-2 block">Telefone</label>
                    <input class="w-full rounded border-[1.5px] border-stroke bg-gray-2 py-3 px-5 text-sm text-black focus:border-primary focus:outline-none" type="text" name="telefone" value="{{ old('telefone', $paciente->person->phone) }}" />
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-700 mb-2 block">Whatsapp</label>
                    <input class="w-full rounded border-[1.5px] border-stroke bg-gray-2 py-3 px-5 text-sm text-black focus:border-primary focus:outline-none" type="text" name="whatsapp" value="{{ old('whatsapp', $paciente->person->whatsapp) }}" />
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-700 mb-2 block">E-mail</label>
                    <input class="w-full rounded border-[1.5px] border-stroke bg-gray-2 py-3 px-5 text-sm text-black focus:border-primary focus:outline-none" type="email" name="email" value="{{ old('email', $paciente->person->email) }}" />
                </div>
            </div>
        </div>
        <div class="rounded-sm border border-stroke bg-gray-50 p-4">
            <h2 class="mb-4 text-sm font-medium text-gray-700">Endereço</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="text-sm font-medium text-gray-700 mb-2 block">CEP</label>
                    <input class="w-full rounded border-[1.5px] border-stroke bg-gray-2 py-3 px-5 text-sm text-black focus:border-primary focus:outline-none" type="text" name="cep" value="{{ old('cep', $paciente->person->cep) }}" />
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-700 mb-2 block">Logradouro</label>
                    <input class="w-full rounded border-[1.5px] border-stroke bg-gray-2 py-3 px-5 text-sm text-black focus:border-primary focus:outline-none" type="text" name="logradouro" value="{{ old('logradouro', $paciente->person->logradouro) }}" />
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-700 mb-2 block">Número</label>
                    <input class="w-full rounded border-[1.5px] border-stroke bg-gray-2 py-3 px-5 text-sm text-black focus:border-primary focus:outline-none" type="text" name="numero" value="{{ old('numero', $paciente->person->numero) }}" />
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-700 mb-2 block">Complemento</label>
                    <input class="w-full rounded border-[1.5px] border-stroke bg-gray-2 py-3 px-5 text-sm text-black focus:border-primary focus:outline-none" type="text" name="complemento" value="{{ old('complemento', $paciente->person->complemento) }}" />
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-700 mb-2 block">Bairro</label>
                    <input class="w-full rounded border-[1.5px] border-stroke bg-gray-2 py-3 px-5 text-sm text-black focus:border-primary focus:outline-none" type="text" name="bairro" value="{{ old('bairro', $paciente->person->bairro) }}" />
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-700 mb-2 block">Cidade</label>
                    <input class="w-full rounded border-[1.5px] border-stroke bg-gray-2 py-3 px-5 text-sm text-black focus:border-primary focus:outline-none" type="text" name="cidade" value="{{ old('cidade', $paciente->person->cidade) }}" />
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-700 mb-2 block">Estado</label>
                    <input class="w-full rounded border-[1.5px] border-stroke bg-gray-2 py-3 px-5 text-sm text-black focus:border-primary focus:outline-none" type="text" name="estado" value="{{ old('estado', $paciente->person->estado) }}" />
                </div>
            </div>
        </div>
        <div class="flex justify-between pt-4">
            <a href="{{ route('pacientes.index') }}" class="py-2 px-4 rounded border border-stroke text-gray-700">Cancelar</a>
            <button type="submit" class="py-2 px-4 bg-blue-600 text-white rounded hover:bg-blue-700">Salvar Paciente</button>
        </div>
        </form>
    </div>
    <div x-show="activeTab === 'documentos'" x-cloak>
        <p class="text-gray-700">Seção de documentos.</p>
    </div>
    <div x-show="activeTab === 'financeiro'" x-cloak>
        <p class="text-gray-700">Informações financeiras.</p>
    </div>
    <div x-show="activeTab === 'agendamentos'" x-cloak>
        <p class="text-gray-700">Agenda do paciente.</p>
    </div>
    <div x-show="activeTab === 'plano_tratamento'" x-cloak>
        <p class="text-gray-700">Plano de tratamento.</p>
    </div>
</div>
@endsection
