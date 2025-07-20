@extends('layouts.app')

@section('content')
<div class="flex items-start justify-between mb-6">
    <div>
        <h1 class="text-2xl font-bold">Dashboard</h1>
        <p class="text-gray-600">Visão geral de todas as unidades</p>
    </div>
    <div class="flex items-center space-x-2">
        <x-dashboard.select-unidade :options="['Centro', 'Norte', 'Sul']" />
        <a href="#" class="py-2 px-4 bg-primary text-white rounded hover:bg-primary/90 text-sm">+ Novo Profissional</a>
    </div>
</div>
<div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4 mb-6">
    <x-dashboard.card title="Total de Pacientes" value="350" comparison="+15% em relação ao mês anterior" :icon="'<svg xmlns=\"http://www.w3.org/2000/svg\" class=\"w-6 h-6\" fill=\"none\" viewBox=\"0 0 24 24\" stroke=\"currentColor\"><path stroke-linecap=\"round\" stroke-linejoin=\"round\" stroke-width=\"2\" d=\"M5.121 17.804A13.937 13.937 0 0112 15c2.33 0 4.5.533 6.879 1.532M15 11a3 3 0 11-6 0 3 3 0 016 0z\" /></svg>'" />
    <x-dashboard.card title="Consultas Hoje" value="28" comparison="+5% em relação ao mês anterior" :icon="'<svg xmlns=\"http://www.w3.org/2000/svg\" class=\"w-6 h-6\" fill=\"none\" viewBox=\"0 0 24 24\" stroke=\"currentColor\"><path stroke-linecap=\"round\" stroke-linejoin=\"round\" stroke-width=\"2\" d=\"M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z\" /></svg>'" />
    <x-dashboard.card title="Cancelamentos Hoje" value="3" comparison="+2 em relação ao mês anterior" :icon="'<svg xmlns=\"http://www.w3.org/2000/svg\" class=\"w-6 h-6\" fill=\"none\" viewBox=\"0 0 24 24\" stroke=\"currentColor\"><path stroke-linecap=\"round\" stroke-linejoin=\"round\" stroke-width=\"2\" d=\"M17 20h5v-2a4 4 0 00-4-4h-1M9 20H4v-2a4 4 0 014-4h1m4-8a4 4 0 110 8 4 4 0 010-8z\" /></svg>'" />
    <x-dashboard.card title="Faturamento Diário" value="R$ 12.000" comparison="+10% em relação ao mês anterior" :icon="'<svg xmlns=\"http://www.w3.org/2000/svg\" class=\"w-6 h-6\" fill=\"none\" viewBox=\"0 0 24 24\" stroke=\"currentColor\"><path stroke-linecap=\"round\" stroke-linejoin=\"round\" stroke-width=\"2\" d=\"M12 8c-1.657 0-3 1.343-3 3s1.343 3 3 3 3 1.343 3 3-1.343 3-3 3m0-12V4m0 16v-4m0 4c1.657 0 3-1.343 3-3s-1.343-3-3-3-3-1.343-3-3 1.343-3 3-3\" /></svg>'" />
</div>
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
    <x-dashboard.chart title="Consultas do Dia">
        <ul class="mt-4 space-y-1 text-sm">
            <li class="flex justify-between"><span>Agendadas</span><span>20</span></li>
            <li class="flex justify-between"><span>Confirmadas</span><span>15</span></li>
            <li class="flex justify-between"><span>Canceladas</span><span>3</span></li>
            <li class="flex justify-between"><span>Realizadas</span><span>12</span></li>
        </ul>
    </x-dashboard.chart>
    <x-dashboard.chart title="Ocupação Semanal">
        <x-slot:header>
            <span class="text-sm text-gray-500">Taxa de ocupação atual: 78%</span>
        </x-slot:header>
    </x-dashboard.chart>
</div>
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <x-dashboard.chart title="Taxa de Cancelamentos e Faltas (30 dias)" />
    <x-dashboard.chart title="Principais Procedimentos" />
</div>
@endsection
