@extends('layouts.app')

@section('content')
@include('partials.breadcrumbs', ['crumbs' => [
    ['label' => 'Dashboard', 'url' => route('admin.index')],
    ['label' => 'Profissionais', 'url' => route('profissionais.index')],
    ['label' => 'Visualizar']
]])
<div class="w-full bg-white p-6 rounded-lg shadow">
    <h1 class="text-xl font-semibold mb-4">Detalhes do Profissional</h1>
    <div class="border-b mb-6">
        <nav class="-mb-px flex space-x-4" aria-label="Tabs">
            <a href="{{ route('profissionais.edit', $profissional) }}" class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300">Dados cadastrais</a>
            <span class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm text-gray-500">Dados admissionais</span>
            <button type="button" class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm border-blue-500 text-blue-600" disabled>Remuneração</button>
        </nav>
    </div>
    <div>
        <x-accordion-section title="Remuneração e Comissionamento" :open="true">
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-4">
                <div class="sm:col-span-2 flex space-x-2">
                    <div class="flex-1">
                        <span class="text-sm font-medium text-gray-700 mb-2 block">Salário fixo</span>
                        <p class="text-gray-900">{{ $profissional->salario_fixo ?? '-' }}</p>
                    </div>
                    <div>
                        <span class="text-sm font-medium text-gray-700 mb-2 block">Período</span>
                        <p class="text-gray-900">{{ $profissional->salario_periodo ?? '-' }}</p>
                    </div>
                </div>
            </div>
            <div class="space-y-4">
                @foreach($clinics as $clinic)
                    @php $comissao = $profissional->comissoes->firstWhere('clinica_id', $clinic->id); @endphp
                    <div class="p-4 bg-gray-50 border rounded">
                        <h4 class="text-sm font-medium text-gray-700 mb-2">{{ $clinic->nome }}</h4>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <span class="text-sm font-medium text-gray-700 mb-2 block">% de comissão</span>
                                <p class="text-gray-900">{{ $comissao->comissao ?? '-' }}</p>
                            </div>
                            <div>
                                <span class="text-sm font-medium text-gray-700 mb-2 block">% prótese</span>
                                <p class="text-gray-900">{{ $comissao->protese ?? '-' }}</p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </x-accordion-section>
        <x-accordion-section title="Dados bancários">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <span class="text-sm font-medium text-gray-700 mb-2 block">Nome do banco</span>
                    <p class="text-gray-900">{{ $profissional->conta['nome_banco'] ?? '-' }}</p>
                </div>
                <div>
                    <span class="text-sm font-medium text-gray-700 mb-2 block">Tipo de conta</span>
                    <p class="text-gray-900">{{ $profissional->conta['tipo'] ?? '-' }}</p>
                </div>
                <div>
                    <span class="text-sm font-medium text-gray-700 mb-2 block">Agência</span>
                    <p class="text-gray-900">{{ $profissional->conta['agencia'] ?? '-' }}</p>
                </div>
                <div>
                    <span class="text-sm font-medium text-gray-700 mb-2 block">Número da conta</span>
                    <p class="text-gray-900">{{ $profissional->conta['numero'] ?? '-' }}</p>
                </div>
                <div>
                    <span class="text-sm font-medium text-gray-700 mb-2 block">CPF/CNPJ do titular</span>
                    <p class="text-gray-900">{{ $profissional->conta['cpf_cnpj'] ?? '-' }}</p>
                </div>
                <div class="sm:col-span-2">
                    <span class="text-sm font-medium text-gray-700 mb-2 block">Chave PIX</span>
                    <p class="text-gray-900">{{ $profissional->chave_pix ?? '-' }}</p>
                </div>
            </div>
        </x-accordion-section>
    </div>
</div>
@endsection
