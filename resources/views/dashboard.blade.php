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
    <x-dashboard.card title="Profissionais" value="12" comparison="+2 em relação ao mês anterior" :icon="'<svg xmlns=\"http://www.w3.org/2000/svg\" class=\"w-6 h-6\" fill=\"none\" viewBox=\"0 0 24 24\" stroke=\"currentColor\"><path stroke-linecap=\"round\" stroke-linejoin=\"round\" stroke-width=\"2\" d=\"M17 20h5v-2a4 4 0 00-4-4h-1M9 20H4v-2a4 4 0 014-4h1m4-8a4 4 0 110 8 4 4 0 010-8z\" /></svg>'" />
    <x-dashboard.card title="Faturamento Mensal" value="R$ 12.000" comparison="+10% em relação ao mês anterior" :icon="'<svg xmlns=\"http://www.w3.org/2000/svg\" class=\"w-6 h-6\" fill=\"none\" viewBox=\"0 0 24 24\" stroke=\"currentColor\"><path stroke-linecap=\"round\" stroke-linejoin=\"round\" stroke-width=\"2\" d=\"M12 8c-1.657 0-3 1.343-3 3s1.343 3 3 3 3 1.343 3 3-1.343 3-3 3m0-12V4m0 16v-4m0 4c1.657 0 3-1.343 3-3s-1.343-3-3-3-3-1.343-3-3 1.343-3 3-3\" /></svg>'" />
</div>
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
    <x-dashboard.chart title="Consultas do Dia" class="lg:col-span-2" />
    <div class="space-y-4">
        <x-dashboard.chart title="Ocupação Semanal">
            <x-slot:header>
                <span class="text-sm text-gray-500">Taxa de ocupação atual: 78%</span>
            </x-slot:header>
        </x-dashboard.chart>
        <div class="grid grid-cols-2 gap-4">
            <div class="p-4 bg-white rounded-lg shadow text-center">
                <p class="text-sm text-gray-500">Cancelamentos (30 dias)</p>
                <p class="mt-2 text-2xl text-red-600 font-semibold">12%</p>
            </div>
            <div class="p-4 bg-white rounded-lg shadow text-center">
                <p class="text-sm text-gray-500">No-shows (30 dias)</p>
                <p class="mt-2 text-2xl text-red-600 font-semibold">8%</p>
            </div>
        </div>
    </div>
</div>
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <x-dashboard.table title="Próximas Consultas" :headings="['Horário','Nome','Unidade','Tipo']">
        <tr>
            <td class="px-4 py-2">09:00</td>
            <td class="px-4 py-2">Ana Souza</td>
            <td class="px-4 py-2">Centro</td>
            <td class="px-4 py-2">Avaliação</td>
        </tr>
        <tr>
            <td class="px-4 py-2">10:30</td>
            <td class="px-4 py-2">Carlos Silva</td>
            <td class="px-4 py-2">Norte</td>
            <td class="px-4 py-2">Limpeza</td>
        </tr>
        <x-slot:header>
            <a href="#" class="text-sm text-primary hover:underline">Ver Agenda</a>
        </x-slot:header>
    </x-dashboard.table>
    <x-dashboard.table title="Equipe Profissional" :headings="['Nome','Unidade','Especialidade']">
        <tr>
            <td class="px-4 py-2">Dr. João</td>
            <td class="px-4 py-2">Centro</td>
            <td class="px-4 py-2">Ortodontia</td>
        </tr>
        <tr>
            <td class="px-4 py-2">Dra. Maria</td>
            <td class="px-4 py-2">Sul</td>
            <td class="px-4 py-2">Implantodontia</td>
        </tr>
        <x-slot:header>
            <a href="#" class="text-sm text-primary hover:underline">Ver Todos</a>
        </x-slot:header>
    </x-dashboard.table>
</div>
@endsection
