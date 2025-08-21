@extends('layouts.app')

@section('content')
@include('partials.breadcrumbs', ['crumbs' => [
    ['label' => 'Dashboard', 'url' => route('admin.index')],
    ['label' => 'Financeiro']
]])

<div class="flex items-start justify-between mb-6">
    <div>
        <h1 class="text-2xl font-bold">Gestão Financeira</h1>
        <p class="text-gray-600">Controle financeiro completo do consultório</p>
    </div>
    <div class="flex items-center gap-2">
        <x-dashboard.select-unidade label="Todas as Clínicas" :options="$clinics" />
        <a href="#" class="py-2 px-4 bg-emerald-600 text-white rounded hover:bg-emerald-700 text-sm">+ Novo Recebimento</a>
        <a href="#" class="py-2 px-4 bg-red-600 text-white rounded hover:bg-red-700 text-sm">+ Novo Pagamento</a>
        <a href="#" class="p-2 border rounded text-sm" title="Relatórios">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19V9m4 10V5m4 14v-7M5 19V5" /></svg>
        </a>
    </div>
</div>

<div class="mb-6 border-b">
    <nav class="flex space-x-4">
        <button class="pb-2 text-sm font-medium border-b-2 border-emerald-600 text-emerald-600">Diário</button>
        <button class="pb-2 text-sm font-medium text-gray-600 hover:text-emerald-600">Semanal</button>
        <button class="pb-2 text-sm font-medium text-gray-600 hover:text-emerald-600">Mensal</button>
        <button class="pb-2 text-sm font-medium text-gray-600 hover:text-emerald-600">Anual</button>
        <button class="pb-2 text-sm font-medium text-gray-600 hover:text-emerald-600">Personalizado</button>
    </nav>
</div>

<div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-6 mb-6">
    <x-dashboard.stats-card title="Saldo Atual" value="R$ 12.345,67" valueClass="text-emerald-600" />
    <x-dashboard.stats-card title="Receitas (Mês)" value="R$ 25.000,00" valueClass="text-emerald-600" comparison="↑ 5% vs mês anterior" />
    <x-dashboard.stats-card title="Despesas (Mês)" value="R$ 18.000,00" valueClass="text-red-600" comparison="↓ 3% vs mês anterior" />
    <x-dashboard.stats-card title="A Receber" value="R$ 4.500,00" valueClass="text-orange-500" comparison="3 pendências" />
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
    <x-dashboard.chart title="Comparativo entre Clínicas" chartId="comparativoClinicas" height="h-80" />
    <x-dashboard.chart title="Fluxo de Caixa (6 meses)" chartId="fluxoCaixa" height="h-80" />
    <div class="lg:col-span-2">
        <x-dashboard.chart title="Formas de Pagamento" chartId="formasPagamento" height="h-80" />
    </div>
</div>

<div class="mt-6">
    <div class="flex space-x-4 border-b">
        <button class="pb-2 text-sm font-medium border-b-2 border-emerald-600 text-emerald-600">Fluxo de Caixa</button>
        <button class="pb-2 text-sm font-medium text-gray-600 hover:text-emerald-600">Receitas</button>
        <button class="pb-2 text-sm font-medium text-gray-600 hover:text-emerald-600">Despesas</button>
        <button class="pb-2 text-sm font-medium text-gray-600 hover:text-emerald-600">Relatórios</button>
    </div>
    <div class="p-6 bg-white rounded-b-lg">
        <p class="text-sm text-gray-600">Conteúdo de exemplo para Fluxo de Caixa.</p>
    </div>
</div>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    const ctxClinicas = document.getElementById('comparativoClinicas').getContext('2d');
    let chartClinicas;
    function renderClinicas() {
        if (chartClinicas) chartClinicas.destroy();
        chartClinicas = new Chart(ctxClinicas, {
            type: 'bar',
            data: {
                labels: ['Centro', 'Norte', 'Sul'],
                datasets: [
                    { label: 'Receitas', data: [12, 19, 3], backgroundColor: 'rgba(16,185,129,0.5)' },
                    { label: 'Despesas', data: [8, 11, 5], backgroundColor: 'rgba(239,68,68,0.5)' }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false
            }
        });
    }
    renderClinicas();

    const ctxFluxo = document.getElementById('fluxoCaixa').getContext('2d');
    let chartFluxo;
    function renderFluxo() {
        if (chartFluxo) chartFluxo.destroy();
        chartFluxo = new Chart(ctxFluxo, {
            type: 'bar',
            data: {
                labels: ['Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun'],
                datasets: [
                    { label: 'Saldo', data: [5, 10, 8, 12, 7, 9], backgroundColor: 'rgba(59,130,246,0.5)' }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false
            }
        });
    }
    renderFluxo();

    const ctxPag = document.getElementById('formasPagamento').getContext('2d');
    let chartPag;
    function renderPag() {
        if (chartPag) chartPag.destroy();
        chartPag = new Chart(ctxPag, {
            type: 'doughnut',
            data: {
                labels: ['Dinheiro', 'Cartão', 'Pix'],
                datasets: [{
                    data: [30, 50, 20],
                    backgroundColor: ['#10B981', '#3B82F6', '#F59E0B']
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false
            }
        });
    }
    renderPag();
});
</script>
@endpush

