@extends('layouts.app')

@section('content')
@include('partials.breadcrumbs', ['crumbs' => [
    ['label' => 'Dashboard', 'url' => route('admin.index')],
    ['label' => 'Profissionais']
]])
<div class="mb-6 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
    <div>
        <h1 class="text-2xl font-bold">Profissionais</h1>
        <p class="text-gray-600">Gestão de profissionais e funcionários</p>
    </div>
    <div class="flex items-center gap-2">
        <a href="{{ route('profissionais.create') }}" class="py-2 px-4 bg-blue-600 text-white rounded hover:bg-blue-700 text-sm">+ Novo Profissional</a>
        <div class="relative" x-data="{ open: false }">
            <button @click="open = !open" class="py-2 px-4 bg-white border rounded text-sm flex items-center gap-1">
                Relatórios
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                </svg>
            </button>
            <div x-show="open" @click.away="open = false" x-cloak class="absolute right-0 mt-2 w-40 bg-white border rounded shadow text-sm">
                <a href="#" class="block px-4 py-2 hover:bg-gray-100">Relatório 1</a>
                <a href="#" class="block px-4 py-2 hover:bg-gray-100">Relatório 2</a>
            </div>
        </div>
        <button class="py-2 px-4 bg-white border rounded text-sm flex items-center gap-1">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zm0 6a1 1 0 011-1h16a1 1 0 011 1v10a1 1 0 01-1 1H4a1 1 0 01-1-1V10z" />
            </svg>
            Mais Filtros
        </button>
    </div>
</div>

<div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
    <x-dashboard.stats-card :title="'Total de Profissionais'" :value="$totalProfissionais" :comparison="$dentistas.' Dentistas | '.$auxiliares.' Auxiliares'" />
    <x-dashboard.stats-card title="Atendimentos (Último mês)" value="285" comparison="+12% em relação ao mês anterior" />
    <x-dashboard.stats-card title="Comissões (Total do mês)" value="R$ 15.430,00" comparison="Média de R$ 1.285,83 por profissional" />
</div>

<div class="mb-4">
    <label class="block text-sm font-medium text-gray-700 mb-1" for="clinica_id">Filtrar por Clínica</label>
    <select id="clinica_id" name="clinica_id" class="border-gray-300 rounded px-3 py-2 text-sm">
        <option value="">Todas as Clínicas</option>
        @foreach($clinicas as $clinica)
            <option value="{{ $clinica->id }}">{{ $clinica->nome }}</option>
        @endforeach
    </select>
</div>
<div class="bg-white rounded-lg shadow overflow-x-auto">
    <table class="min-w-full divide-y divide-gray-200 text-sm">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-4 py-2 text-left font-medium text-gray-500 uppercase">Profissional</th>
                <th class="px-4 py-2 text-left font-medium text-gray-500 uppercase">Cargo</th>
                <th class="px-4 py-2 text-left font-medium text-gray-500 uppercase">Clínicas</th>
                <th class="px-4 py-2 text-left font-medium text-gray-500 uppercase">Contato</th>
                <th class="px-4 py-2 text-left font-medium text-gray-500 uppercase">Ações</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-200">
            @forelse($profissionais as $profissional)
            <tr>
                <td class="px-4 py-2 whitespace-nowrap">
                    <div class="flex items-center space-x-2">
                        @php
                            $person = optional($profissional->user)->person ?? $profissional->person;
                            $initials = strtoupper(substr($person->first_name, 0, 1) . substr($person->last_name, 0, 1));
                        @endphp
                        @if($person->photo_path)
                            <img src="{{ asset('storage/' . $person->photo_path) }}" alt="{{ $person->first_name }}" class="w-8 h-8 rounded-full object-cover" />
                        @else
                            <div class="w-8 h-8 bg-gray-200 rounded-full flex items-center justify-center text-gray-700 uppercase">{{ $initials }}</div>
                        @endif
                        <div>
                            <div class="font-medium text-gray-700">
                                {{ optional($profissional->user->person)->first_name ?? $profissional->person->first_name }}
                                {{ optional($profissional->user->person)->last_name ?? $profissional->person->last_name }}
                            </div>
                            @if(optional($profissional->user)->especialidade)
                                <div class="text-xs text-gray-500">
                                    {{ optional($profissional->user)->especialidade }}
                                </div>
                            @endif
                        </div>
                    </div>
                </td>
                <td class="px-4 py-2 whitespace-nowrap">{{ $profissional->cargo ?? '-' }}</td>
                <td class="px-4 py-2 whitespace-nowrap space-x-1">
                    @foreach($profissional->clinics as $clinic)
                        <span class="inline-block px-2 py-1 bg-gray-100 rounded text-xs text-gray-600">{{ $clinic->nome }}</span>
                    @endforeach
                </td>
                <td class="px-4 py-2 whitespace-nowrap">
                    <div>{{ optional($profissional->user->person)->email ?? $profissional->person->email }}</div>
                    <div class="text-xs text-gray-500">{{ optional($profissional->user->person)->phone ?? $profissional->person->phone }}</div>
                </td>
                <td class="px-4 py-2 whitespace-nowrap text-center">
                    <div class="flex items-center justify-center space-x-2">
                        <a href="{{ route('profissionais.edit', $profissional) }}" class="text-gray-600 hover:text-blue-600" title="Editar">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m0 0a2.5 2.5 0 01-3.536 3.536L9 20.036l-4 1 1-4 6.232-6.232a2.5 2.5 0 013.536-3.536z" />
                            </svg>
                        </a>
                        <a href="#" class="text-gray-600 hover:text-blue-600" title="Ver agenda">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </a>
                        <a href="#" class="text-gray-600 hover:text-blue-600" title="Financeiro">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 1.343-3 3s1.343 3 3 3 3 1.343 3 3-1.343 3-3 3m0-12V4m0 16v-4m0 4c1.657 0 3-1.343 3-3s-1.343-3-3-3-3-1.343-3-3 1.343-3 3-3" />
                            </svg>
                        </a>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" class="px-4 py-2 text-center">Nenhum profissional cadastrado.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
