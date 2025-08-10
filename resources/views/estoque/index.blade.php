@extends('layouts.app')

@section('content')
@include('partials.breadcrumbs', ['crumbs' => [
    ['label' => 'Dashboard', 'url' => route('admin.index')],
    ['label' => 'Estoque e Suprimentos']
]])
<div class="flex items-start justify-between mb-6">
    <div>
        <h1 class="text-2xl font-bold">Estoque e Suprimentos</h1>
        <p class="text-gray-600">Gestão completa de materiais odontológicos e EPIs</p>
    </div>
    <div class="flex items-center gap-2">
        <x-dashboard.select-unidade :options="$clinics" />
        <a href="#" class="py-2 px-4 bg-emerald-600 text-white rounded hover:bg-emerald-700 text-sm">+ Novo Produto</a>
        <a href="#" class="py-2 px-4 border rounded text-sm">Pedidos</a>
        <a href="#" class="py-2 px-4 border rounded text-sm">Transferências</a>
        <a href="#" class="py-2 px-4 border rounded text-sm">Relatórios</a>
    </div>
</div>
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    <x-dashboard.stats-card title="Total de Itens" :value="$produtos->count().' itens • '.collect($produtos)->pluck('categoria')->unique()->count().' categorias'" />
    <x-dashboard.stats-card title="Itens Críticos" :value="$itensCriticos->count()" />
    <x-dashboard.stats-card title="Valor do Estoque" :value="'R$ '.number_format($valorEstoque,2,',','.')" :comparison="'Atualizado em '.$ultimaAtualizacao" />
    <x-dashboard.stats-card title="Pedidos Pendentes" :value="random_int(0,10)" comparison="Aguardando entrega" />
</div>
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <div class="lg:col-span-2 space-y-6">
        <x-dashboard.table title="Produtos em Estoque" :headings="['Produto','Categoria','Centro','Norte','Sul','Total','Estoque Mínimo','Status','Último Abastecimento','Ações']">
            @foreach($produtos->sortBy('nome') as $p)
                <tr>
                    <td class="px-4 py-2 whitespace-nowrap">{{ $p['nome'] }}</td>
                    <td class="px-4 py-2 whitespace-nowrap">{{ $p['categoria'] }}</td>
                    <td class="px-4 py-2 text-center {{ $p['centro'] < $p['minimo'] ? 'text-red-600' : '' }}">{{ $p['centro'] }}</td>
                    <td class="px-4 py-2 text-center {{ $p['norte'] < $p['minimo'] ? 'text-red-600' : '' }}">{{ $p['norte'] }}</td>
                    <td class="px-4 py-2 text-center {{ $p['sul'] < $p['minimo'] ? 'text-red-600' : '' }}">{{ $p['sul'] }}</td>
                    <td class="px-4 py-2 text-center {{ $p['total'] < $p['minimo'] ? 'text-red-600' : '' }}">{{ $p['total'] }}</td>
                    <td class="px-4 py-2 text-center">{{ $p['minimo'] }}</td>
                    <td class="px-4 py-2 text-center">
                        <span class="font-semibold {{ $p['status']==='Baixo' ? 'text-red-600' : 'text-emerald-600' }}">{{ $p['status'] }}</span>
                    </td>
                    <td class="px-4 py-2 text-center">{{ $p['ultima_compra'] }}</td>
                    <td class="px-4 py-2 whitespace-nowrap">
                        <div class="flex items-center space-x-2">
                            <a href="#" class="text-gray-600 hover:text-blue-600" title="Detalhar">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                            </a>
                            <a href="#" class="text-gray-600 hover:text-blue-600" title="Editar">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m0 0a2.5 2.5 0 01-3.536 3.536L9 20.036l-4 1 1-4 6.232-6.232a2.5 2.5 0 013.536-3.536z" /></svg>
                            </a>
                            <button class="text-red-600 hover:text-red-800" title="Excluir">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6M1 7h22M10 7V4a1 1 0 011-1h2a1 1 0 011 1v3" /></svg>
                            </button>
                        </div>
                    </td>
                </tr>
            @endforeach
        </x-dashboard.table>
    </div>
    <div class="space-y-6">
        <div class="bg-white border border-red-200 rounded-lg p-4">
            <h3 class="text-lg font-semibold mb-2">Alertas de Estoque</h3>
            <ul class="space-y-2 text-sm">
                @forelse($itensCriticos as $c)
                    <li class="flex justify-between items-center">
                        <div>
                            <p class="font-medium">{{ $c['nome'] }} <span class="text-red-600">{{ $c['total'] }}</span> / {{ $c['minimo'] }}</p>
                            <p class="text-xs text-gray-500">Centro: {{ $c['centro'] }}, Norte: {{ $c['norte'] }}, Sul: {{ $c['sul'] }}</p>
                        </div>
                        <a href="#" class="py-1 px-2 bg-blue-600 text-white rounded text-xs">Pedir</a>
                    </li>
                @empty
                    <li class="text-sm text-gray-500">Sem alertas.</li>
                @endforelse
            </ul>
        </div>
        <div class="bg-white rounded-lg p-4 shadow">
            <h3 class="text-lg font-semibold mb-2">Consumo Recente</h3>
            <ul class="divide-y divide-gray-200 text-sm">
                @foreach($consumoRecente as $c)
                    <li class="py-2 flex justify-between items-start">
                        <div>
                            <p class="font-medium">{{ $c['nome'] }}</p>
                            <p class="text-xs text-gray-500">Centro: {{ $c['centro'] }}, Norte: {{ $c['norte'] }}, Sul: {{ $c['sul'] }}</p>
                        </div>
                        <span class="text-xs text-gray-600">{{ $c['responsavel'] }}</span>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>
</div>
@endsection
