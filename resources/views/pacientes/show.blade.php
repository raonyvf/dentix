@extends('layouts.app')

@section('content')
@include('partials.breadcrumbs', ['crumbs' => [
    ['label' => 'Dashboard', 'url' => route('admin.index')],
    ['label' => 'Pacientes', 'url' => route('pacientes.index')],
    ['label' => 'Visualizar']
]])
<div class="bg-white rounded-lg shadow p-6" x-data="{ activeTab: 'dados' }">
    <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between mb-6">
        <div class="flex items-start space-x-4">
            <span class="w-12 h-12 rounded-full bg-primary/10 text-primary flex items-center justify-center text-xl font-semibold">
                {{ strtoupper(substr($paciente->nome,0,1) . substr($paciente->ultimo_nome,0,1)) }}
            </span>
            <div>
                <h1 class="text-2xl font-semibold text-gray-700">{{ $paciente->nome }} {{ $paciente->ultimo_nome }}</h1>
                <p class="text-sm text-gray-500">
                    {{ $paciente->data_nascimento?->format('d/m/Y') }}
                    @if($paciente->menor_idade)
                        • Menor de idade
                    @endif
                </p>
                @if($paciente->menor_idade)
                    <p class="text-sm text-gray-500">Responsável: {{ $paciente->responsavel_nome }} (CPF {{ $paciente->responsavel_cpf }})</p>
                    <p class="text-sm text-gray-500">{{ $paciente->responsavel_telefone }} • {{ $paciente->responsavel_email }}</p>
                @endif
            </div>
        </div>
        <div class="flex mt-4 sm:mt-0 space-x-2">
            <a href="#" class="px-4 py-2 bg-primary text-white rounded hover:bg-primary/90">Agendar Consulta</a>
            <a href="#" class="px-4 py-2 bg-emerald-600 text-white rounded hover:bg-emerald-700">Novo Orçamento</a>
        </div>
    </div>
    <div class="border-b mb-4">
        <nav class="-mb-px flex space-x-4" aria-label="Tabs">
            <button type="button" @click="activeTab = 'dados'" :class="activeTab === 'dados' ? 'border-primary text-primary' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'" class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                Dados Pessoais
            </button>
            <button type="button" @click="activeTab = 'documentos'" :class="activeTab === 'documentos' ? 'border-primary text-primary' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'" class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                Documentos
            </button>
            <button type="button" @click="activeTab = 'financeiro'" :class="activeTab === 'financeiro' ? 'border-primary text-primary' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'" class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                Financeiro
            </button>
            <button type="button" @click="activeTab = 'agendamentos'" :class="activeTab === 'agendamentos' ? 'border-primary text-primary' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'" class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                Agendamentos
            </button>
            <button type="button" @click="activeTab = 'plano'" :class="activeTab === 'plano' ? 'border-primary text-primary' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'" class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                Plano de Tratamento
            </button>
        </nav>
    </div>
    <section x-show="activeTab === 'dados'">
        <p class="text-gray-700">Conteúdo de Dados Pessoais</p>
    </section>
    <section x-show="activeTab === 'documentos'" x-cloak>
        <p class="text-gray-700">Conteúdo de Documentos</p>
    </section>
    <section x-show="activeTab === 'financeiro'" x-cloak>
        <p class="text-gray-700">Conteúdo de Financeiro</p>
    </section>
    <section x-show="activeTab === 'agendamentos'" x-cloak>
        <p class="text-gray-700">Conteúdo de Agendamentos</p>
    </section>
    <section x-show="activeTab === 'plano'" x-cloak>
        <p class="text-gray-700">Conteúdo de Plano de Tratamento</p>
    </section>
</div>
@endsection
