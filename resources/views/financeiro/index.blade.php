@extends('layouts.app')

@section('content')
@include('partials.breadcrumbs', ['crumbs' => [
    ['label' => 'Dashboard', 'url' => route('admin.index')],
    ['label' => 'Gestão Financeira']
]])
<div class="flex items-start justify-between mb-6">
    <div>
        <h1 class="text-2xl font-bold">Gestão Financeira</h1>
        <p class="text-gray-600">Controle financeiro completo do consultório</p>
    </div>
    <div class="flex items-center gap-2">
        <x-dashboard.select-unidade :options="$clinics" />
        <a href="#" class="py-2 px-4 bg-emerald-600 text-white rounded hover:bg-emerald-700 text-sm">+ Novo Recebimento</a>
        <a href="#" class="py-2 px-4 bg-blue-600 text-white rounded hover:bg-blue-700 text-sm">+ Novo Pagamento</a>
        <a href="#" class="py-2 px-4 border rounded text-sm">📊 Relatórios</a>
    </div>
</div>
<div class="mb-6" x-data="{ filtro: 'Mensal' }">
    <div class="flex gap-2">
        <template x-for="opt in ['Diário','Semanal','Mensal','Anual','Personalizado']" :key="opt">
            <button @click="filtro = opt" :class="filtro === opt ? 'bg-primary text-white' : 'bg-white border text-gray-700'" class="px-3 py-1 rounded text-sm" x-text="opt"></button>
        </template>
    </div>
</div>
@php
    $saldoColor = $saldoAtual < 0 ? 'bg-red-500' : 'bg-emerald-500';
@endphp
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    <x-financeiro.card label="Saldo Atual" :value="'R$ '.number_format($saldoAtual,2,',','.')" :color="$saldoColor" icon="<svg xmlns='http://www.w3.org/2000/svg' class='w-6 h-6' fill='none' viewBox='0 0 24 24' stroke='currentColor'><path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M12 8c-1.657 0-3 1.343-3 3s1.343 3 3 3 3 1.343 3 3-1.343 3-3 3m0-12V4m0 16v-4m0 4c1.657 0 3-1.343 3-3s-1.343-3-3-3-3-1.343-3-3 1.343-3 3-3'/></svg>" />
    <x-financeiro.card label="Receitas (Mês)" :value="'R$ '.number_format($receitasMes,2,',','.')" color="bg-emerald-500" icon="<svg xmlns='http://www.w3.org/2000/svg' class='w-6 h-6' fill='none' viewBox='0 0 24 24' stroke='currentColor'><path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M12 8c-1.657 0-3 1.343-3 3s1.343 3 3 3 3 1.343 3 3-1.343 3-3 3m0-12V4m0 16v-4m0 4c1.657 0 3-1.343 3-3s-1.343-3-3-3-3-1.343-3-3 1.343-3 3-3'/></svg>">
        <x-slot name="value">
            R$ {{ number_format($receitasMes, 2, ',', '.') }}
            <span class="block text-xs">{{ $receitasAnterior ? round((($receitasMes - $receitasAnterior)/$receitasAnterior)*100,1) : 0 }}% vs mês anterior</span>
        </x-slot>
    </x-financeiro.card>
    <x-financeiro.card label="Despesas (Mês)" :value="'R$ '.number_format($despesasMes,2,',','.')" color="bg-red-500" icon="<svg xmlns='http://www.w3.org/2000/svg' class='w-6 h-6' fill='none' viewBox='0 0 24 24' stroke='currentColor'><path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6 6 0 10-12 0v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9'/></svg>">
        <x-slot name="value">
            R$ {{ number_format($despesasMes, 2, ',', '.') }}
            <span class="block text-xs">{{ $despesasAnterior ? round((($despesasMes - $despesasAnterior)/$despesasAnterior)*100,1) : 0 }}% vs mês anterior</span>
        </x-slot>
    </x-financeiro.card>
    <x-financeiro.card label="A Receber" :value="'R$ '.number_format($aReceberValor,2,',','.')" color="bg-yellow-500" icon="<svg xmlns='http://www.w3.org/2000/svg' class='w-6 h-6' fill='none' viewBox='0 0 24 24' stroke='currentColor'><path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M3 3h18M9 3v18m6-18v18M3 9h18M3 15h18'/></svg>">
        <x-slot name="value">
            R$ {{ number_format($aReceberValor,2,',','.') }} ({{ $aReceberCount }})
        </x-slot>
    </x-financeiro.card>
</div>
<div class="bg-white rounded-lg shadow p-4 mb-6">
    <div class="mb-2">
        <h3 class="text-lg font-semibold">Comparativo entre Clínicas</h3>
        <p class="text-sm text-gray-500">Desempenho financeiro por unidade no mês atual</p>
    </div>
    <canvas id="clinicas-chart" class="w-full h-72" height="288"></canvas>
</div>
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
    <div class="bg-white rounded-lg shadow p-4">
        <h3 class="text-lg font-semibold mb-2">Fluxo de Caixa</h3>
        <p class="text-sm text-gray-500">Evolução consolidada de receitas e despesas nos últimos 6 meses</p>
        <canvas id="fluxo-chart" class="w-full h-64" height="256"></canvas>
    </div>
    <div class="bg-white rounded-lg shadow p-4">
        <h3 class="text-lg font-semibold mb-2">Formas de Pagamento</h3>
        <p class="text-sm text-gray-500">Distribuição das receitas por forma de pagamento</p>
        <canvas id="formas-chart" class="w-full h-64" height="256"></canvas>
    </div>
</div>
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <div class="bg-white rounded-lg shadow p-4">
        <h3 class="text-lg font-semibold mb-2">Próximos Recebimentos</h3>
        <p class="text-sm text-gray-500 mb-2">Pagamentos previstos para os próximos 7 dias</p>
        <ul class="divide-y divide-gray-200 text-sm">
            @foreach($proximosRecebimentos as $r)
            <li class="py-2 flex justify-between items-center">
                <div>
                    <p class="font-medium">{{ $r['paciente'] }}</p>
                    <p class="text-gray-500">{{ $r['tratamento'] }} • {{ $r['unidade'] }}</p>
                </div>
                <div class="text-right">
                    <p class="{{ 'text-yellow-600' }}">R$ {{ number_format($r['valor'],2,',','.') }}</p>
                    <p class="text-xs text-gray-500">{{ $r['vencimento'] }}</p>
                </div>
            </li>
            @endforeach
        </ul>
    </div>
    <div class="bg-white rounded-lg shadow p-4">
        <h3 class="text-lg font-semibold mb-2">Próximos Pagamentos</h3>
        <p class="text-sm text-gray-500 mb-2">Despesas previstas para os próximos 7 dias</p>
        <ul class="divide-y divide-gray-200 text-sm">
            @foreach($proximosPagamentos as $p)
            <li class="py-2 flex justify-between items-center">
                <div>
                    <p class="font-medium">{{ $p['nome'] }}</p>
                    <p class="text-gray-500">{{ $p['tipo'] }} • {{ $p['unidade'] }}</p>
                </div>
                <div class="text-right">
                    <p class="text-red-600">R$ {{ number_format($p['valor'],2,',','.') }}</p>
                    <p class="text-xs text-gray-500">{{ $p['vencimento'] }}</p>
                </div>
            </li>
            @endforeach
        </ul>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const createChart = (id, store, config) => {
            const el = document.getElementById(id);
            if (!el || !window.Chart) return;

            // Destroy any existing chart instance attached to this id
            if (window[store]) {
                window[store].destroy();
                delete window[store];
            } else {
                const existing = window.Chart.getChart(el);
                if (existing) existing.destroy();
            }

            // Fully reset the canvas before creating a new chart
            const ctx = el.getContext('2d');
            ctx.clearRect(0, 0, el.width, el.height);
            el.width = el.height = 0;
            el.removeAttribute('style');

            // Replace the canvas node to ensure a fresh element
            const freshCanvas = el.cloneNode(true);
            el.replaceWith(freshCanvas);
            el = freshCanvas;

            window[store] = new Chart(el, config);
        };

        createChart('clinicas-chart', 'clinicasChartInstance', {
            type: 'bar',
            data: {
                labels: @json($comparativo->pluck('clinic')),
                datasets: [
                    {
                        label: 'Receita',
                        backgroundColor: '#10b981',
                        data: @json($comparativo->pluck('receita')),
                    },
                    {
                        label: 'Despesa',
                        backgroundColor: '#ef4444',
                        data: @json($comparativo->pluck('despesa')),
                    },
                    {
                        label: 'A Receber',
                        backgroundColor: '#f59e0b',
                        data: @json($comparativo->pluck('areceber')),
                    }
                ]
            },
            options: { responsive: true, maintainAspectRatio: false }
        });

        createChart('fluxo-chart', 'fluxoChartInstance', {
            type: 'bar',
            data: {
                labels: @json($meses->pluck('mes')),
                datasets: [
                    { label: 'Receita', backgroundColor: '#10b981', data: @json($meses->pluck('receita')) },
                    { label: 'Despesa', backgroundColor: '#ef4444', data: @json($meses->pluck('despesa')) }
                ]
            },
            options: { responsive: true, maintainAspectRatio: false }
        });

        createChart('formas-chart', 'formasChartInstance', {
            type: 'doughnut',
            data: {
                labels: @json(collect($formasPagamento)->pluck('label')),
                datasets: [{ data: @json(collect($formasPagamento)->pluck('percent')), backgroundColor: ['#3b82f6','#10b981','#f59e0b','#6366f1'] }]
            },
            options: { responsive: true, maintainAspectRatio: false }
        });
    });
</script>
@endpush