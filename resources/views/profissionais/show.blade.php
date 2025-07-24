@extends('layouts.app')

@section('content')
@include('partials.breadcrumbs', ['crumbs' => [
    ['label' => 'Dashboard', 'url' => route('admin.index')],
    ['label' => 'Profissionais', 'url' => route('profissionais.index')],
    ['label' => 'Visualizar']
]])
<div class="w-full bg-white p-6 rounded-lg shadow" x-data="{ activeTab: 'adm' }">
    <h1 class="text-xl font-semibold mb-4">Detalhes do Profissional</h1>
    <div class="border-b mb-6">
        <nav class="-mb-px flex space-x-4" aria-label="Tabs">
            <a href="{{ route('profissionais.edit', $profissional) }}" class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300">Dados cadastrais</a>
            <button type="button" class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm border-blue-500 text-blue-600" disabled>Dados admissionais</button>
            <span class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm text-gray-500">Remuneração</span>
            <span class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm text-gray-500">Teste</span>
        </nav>
    </div>
    <div>
        <x-accordion-section title="Dados funcionais" :open="true">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <span class="text-sm font-medium text-gray-700 mb-2 block">Número do funcionário</span>
                    <p class="text-gray-900">{{ $profissional->numero_funcionario ?? '-' }}</p>
                </div>
                <div>
                    <span class="text-sm font-medium text-gray-700 mb-2 block">E-mail corporativo</span>
                    <p class="text-gray-900">{{ $profissional->email_corporativo ?? '-' }}</p>
                </div>
                <div>
                    <span class="text-sm font-medium text-gray-700 mb-2 block">Data de admissão</span>
                    <p class="text-gray-900">{{ optional($profissional->data_admissao)->format('d/m/Y') ?? '-' }}</p>
                </div>
                <div>
                    <span class="text-sm font-medium text-gray-700 mb-2 block">Data de demissão</span>
                    <p class="text-gray-900">{{ optional($profissional->data_demissao)->format('d/m/Y') ?? '-' }}</p>
                </div>
            </div>
        </x-accordion-section>
        <x-accordion-section title="Atribuição">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <span class="text-sm font-medium text-gray-700 mb-2 block">Função</span>
                    <p class="text-gray-900">{{ $profissional->funcao ?? '-' }}</p>
                </div>
                <div>
                    <span class="text-sm font-medium text-gray-700 mb-2 block">Cargo</span>
                    <p class="text-gray-900">{{ $profissional->cargo ?? '-' }}</p>
                </div>
            </div>
        </x-accordion-section>
        @if(($profissional->funcao ?? '') === 'Dentista')
        <x-accordion-section title="Registros" :open="true">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <span class="text-sm font-medium text-gray-700 mb-2 block">CRO</span>
                    <p class="text-gray-900">{{ $profissional->cro ?? '-' }}</p>
                </div>
                <div>
                    <span class="text-sm font-medium text-gray-700 mb-2 block">UF do CRO</span>
                    <p class="text-gray-900">{{ $profissional->cro_uf ?? '-' }}</p>
                </div>
            </div>
        </x-accordion-section>
        @endif
    </div>
</div>
@endsection
