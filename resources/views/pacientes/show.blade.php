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
                {{ strtoupper(substr($paciente->pessoa->primeiro_nome,0,1) . substr($paciente->pessoa->ultimo_nome,0,1)) }}
            </span>
            <div>
                <h1 class="text-2xl font-semibold text-gray-700">{{ $paciente->pessoa->primeiro_nome }} {{ $paciente->pessoa->ultimo_nome }}</h1>
                <p class="text-sm text-gray-500">
                    {{ $paciente->pessoa->data_nascimento?->format('d/m/Y') }}
                    @if($paciente->menor_idade)
                        • Menor de idade
                    @endif
                </p>
                @if($paciente->menor_idade)
                    <p class="text-sm text-gray-500">Responsável: {{ $paciente->responsavel_primeiro_nome }} (CPF {{ $paciente->responsavel_cpf }})</p>
                    <p class="text-sm text-gray-500">{{ $paciente->responsavel_telefone }} • {{ $paciente->responsavel_email }}</p>
                @endif
            </div>
        </div>
        <div class="flex mt-4 sm:mt-0 space-x-2">
            <a href="#" class="px-4 py-2 bg-primary text-white rounded hover:bg-primary/90">Agendar Consulta</a>
            <a href="#" class="px-4 py-2 bg-emerald-600 text-white rounded hover:bg-emerald-700">Novo Orçamento</a>
        </div>
    </div>
    <div class="mb-4 border-b border-stroke">
        <nav class="-mb-px flex flex-wrap" aria-label="Tabs">
            <button
                type="button"
                @click="activeTab = 'dados'"
                :class="activeTab === 'dados' ? 'border-primary text-primary' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                class="inline-flex items-center gap-2 py-4 px-4 text-sm font-medium border-b-2">
                <x-lucide-icon name="user" class="w-4 h-4" />
                Dados Pessoais
            </button>
            <button
                type="button"
                @click="activeTab = 'documentos'"
                :class="activeTab === 'documentos' ? 'border-primary text-primary' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                class="inline-flex items-center gap-2 py-4 px-4 text-sm font-medium border-b-2">
                <x-lucide-icon name="folder" class="w-4 h-4" />
                Documentos
            </button>
            <button
                type="button"
                @click="activeTab = 'orcamentos'"
                :class="activeTab === 'orcamentos' ? 'border-primary text-primary' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                class="inline-flex items-center gap-2 py-4 px-4 text-sm font-medium border-b-2">
                <x-lucide-icon name="dollar-sign" class="w-4 h-4" />
                Orçamentos
            </button>
            <button
                type="button"
                @click="activeTab = 'agendamentos'"
                :class="activeTab === 'agendamentos' ? 'border-primary text-primary' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                class="inline-flex items-center gap-2 py-4 px-4 text-sm font-medium border-b-2">
                <x-lucide-icon name="calendar" class="w-4 h-4" />
                Agendamentos
            </button>
            <button
                type="button"
                @click="activeTab = 'plano'"
                :class="activeTab === 'plano' ? 'border-primary text-primary' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                class="inline-flex items-center gap-2 py-4 px-4 text-sm font-medium border-b-2">
                <x-lucide-icon name="heart-pulse" class="w-4 h-4" />
                Plano de Tratamento
            </button>
        </nav>
    </div>
    <section x-show="activeTab === 'dados'">
        <div class="space-y-6">
            <div class="rounded-sm border border-stroke bg-gray-50 p-4">
                <h2 class="mb-4 text-sm font-medium text-gray-700">Informações Básicas</h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <span class="text-sm font-medium text-gray-700 mb-2 block">Nome</span>
                        <p class="text-gray-900">{{ $paciente->pessoa->primeiro_nome }}</p>
                    </div>
                    <div>
                        <span class="text-sm font-medium text-gray-700 mb-2 block">Nome do meio</span>
                        <p class="text-gray-900">{{ $paciente->pessoa->nome_meio ?: '-' }}</p>
                    </div>
                    <div>
                        <span class="text-sm font-medium text-gray-700 mb-2 block">Último nome</span>
                        <p class="text-gray-900">{{ $paciente->pessoa->ultimo_nome }}</p>
                    </div>
                    <div>
                        <span class="text-sm font-medium text-gray-700 mb-2 block">Data de nascimento</span>
                        <p class="text-gray-900">{{ $paciente->pessoa->data_nascimento?->format('d/m/Y') }}</p>
                    </div>
                    <div>
                        <span class="text-sm font-medium text-gray-700 mb-2 block">CPF</span>
                        <p class="text-gray-900">{{ $paciente->pessoa->cpf ?: '-' }}</p>
                    </div>
                    <div>
                        <span class="text-sm font-medium text-gray-700 mb-2 block">Menor de idade?</span>
                        <p class="text-gray-900">{{ $paciente->menor_idade ? 'Sim' : 'Não' }}</p>
                    </div>
                </div>
            </div>

            @if ($paciente->menor_idade)
            <div class="rounded-sm border border-stroke bg-gray-50 p-4">
                <h2 class="mb-4 text-sm font-medium text-gray-700">Dados do Responsável</h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <span class="text-sm font-medium text-gray-700 mb-2 block">Nome do responsável</span>
                        <p class="text-gray-900">{{ $paciente->responsavel_primeiro_nome }}</p>
                    </div>
                    <div>
                        <span class="text-sm font-medium text-gray-700 mb-2 block">Nome do meio</span>
                        <p class="text-gray-900">{{ $paciente->responsavel_nome_meio ?: '-' }}</p>
                    </div>
                    <div>
                        <span class="text-sm font-medium text-gray-700 mb-2 block">Último nome</span>
                        <p class="text-gray-900">{{ $paciente->responsavel_ultimo_nome ?: '-' }}</p>
                    </div>
                    <div>
                        <span class="text-sm font-medium text-gray-700 mb-2 block">CPF do responsável</span>
                        <p class="text-gray-900">{{ $paciente->responsavel_cpf }}</p>
                    </div>
                </div>
            </div>
            @endif

            <div class="rounded-sm border border-stroke bg-gray-50 p-4">
                <h2 class="mb-4 text-sm font-medium text-gray-700">Contato</h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <span class="text-sm font-medium text-gray-700 mb-2 block">Telefone</span>
                        <p class="text-gray-900">{{ $paciente->pessoa->phone ?: '-' }}</p>
                    </div>
                    <div>
                        <span class="text-sm font-medium text-gray-700 mb-2 block">Whatsapp</span>
                        <p class="text-gray-900">{{ $paciente->pessoa->whatsapp ?: '-' }}</p>
                    </div>
                    <div>
                        <span class="text-sm font-medium text-gray-700 mb-2 block">E-mail</span>
                        <p class="text-gray-900">{{ $paciente->pessoa->email ?: '-' }}</p>
                    </div>
                </div>
            </div>

            <div class="rounded-sm border border-stroke bg-gray-50 p-4">
                <h2 class="mb-4 text-sm font-medium text-gray-700">Endereço</h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <span class="text-sm font-medium text-gray-700 mb-2 block">CEP</span>
                        <p class="text-gray-900">{{ $paciente->pessoa->cep ?: '-' }}</p>
                    </div>
                    <div>
                        <span class="text-sm font-medium text-gray-700 mb-2 block">Logradouro</span>
                        <p class="text-gray-900">{{ $paciente->pessoa->logradouro ?: '-' }}</p>
                    </div>
                    <div>
                        <span class="text-sm font-medium text-gray-700 mb-2 block">Número</span>
                        <p class="text-gray-900">{{ $paciente->pessoa->numero ?: '-' }}</p>
                    </div>
                    <div>
                        <span class="text-sm font-medium text-gray-700 mb-2 block">Complemento</span>
                        <p class="text-gray-900">{{ $paciente->pessoa->complemento ?: '-' }}</p>
                    </div>
                    <div>
                        <span class="text-sm font-medium text-gray-700 mb-2 block">Bairro</span>
                        <p class="text-gray-900">{{ $paciente->pessoa->bairro ?: '-' }}</p>
                    </div>
                    <div>
                        <span class="text-sm font-medium text-gray-700 mb-2 block">Cidade</span>
                        <p class="text-gray-900">{{ $paciente->pessoa->cidade ?: '-' }}</p>
                    </div>
                    <div>
                        <span class="text-sm font-medium text-gray-700 mb-2 block">Estado</span>
                        <p class="text-gray-900">{{ $paciente->pessoa->estado ?: '-' }}</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section x-show="activeTab === 'documentos'" x-cloak>
        <p class="text-gray-700">Conteúdo de Documentos</p>
    </section>
    <section x-show="activeTab === 'orcamentos'" x-cloak>
        @include('pacientes.orcamentos')
    </section>
    <section x-show="activeTab === 'agendamentos'" x-cloak>
        <p class="text-gray-700">Conteúdo de Agendamentos</p>
    </section>
    <section x-show="activeTab === 'plano'" x-cloak>
        <p class="text-gray-700">Conteúdo de Plano de Tratamento</p>
    </section>
</div>
@endsection
