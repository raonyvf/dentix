@extends('layouts.app')

@section('content')
@include('partials.breadcrumbs', ['crumbs' => [
    ['label' => 'Dashboard', 'url' => route('admin.index')],
    ['label' => 'Profissionais']
]])
<div class="mb-6 flex items-start justify-between">
    <div>
        <h1 class="text-2xl font-bold">Profissionais</h1>
        <p class="text-gray-600">Gestão de profissionais e funcionários</p>
    </div>
    <div class="flex items-center space-x-2">
        <a href="{{ route('profissionais.create') }}" class="py-2 px-4 bg-blue-600 text-white rounded hover:bg-blue-700 text-sm">+ Novo Profissional</a>
        <div x-data="{ open: false }" class="relative">
            <button @click="open = !open" class="py-2 px-4 bg-white border rounded shadow-sm text-sm flex items-center">
                Relatórios
                <svg class="w-4 h-4 ml-1" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 10.94l3.71-3.71a.75.75 0 111.06 1.06l-4.24 4.24a.75.75 0 01-1.06 0L5.23 8.27a.75.75 0 01.02-1.06z" clip-rule="evenodd"/></svg>
            </button>
            <div x-show="open" @click.away="open = false" class="absolute right-0 mt-2 w-40 bg-white border rounded shadow-lg z-50" x-cloak>
                <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Exportar CSV</a>
            </div>
        </div>
    </div>
</div>
<div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
    <div class="p-4 bg-white rounded-lg shadow">
        <p class="text-sm text-gray-500"><i class="fas fa-user mr-2"></i>Total de Profissionais</p>
        <p class="mt-2 text-3xl font-semibold text-gray-700">{{ $totalProfissionais ?? 0 }}</p>
        <p class="text-xs text-gray-500">Dentistas: {{ $totalDentistas ?? 0 }} | Auxiliares: {{ $totalAuxiliares ?? 0 }}</p>
    </div>
    <div class="p-4 bg-white rounded-lg shadow">
        <p class="text-sm text-gray-500"><i class="fas fa-calendar mr-2"></i>Atendimentos (último mês)</p>
        <p class="mt-2 text-2xl font-semibold text-gray-700">{{ $atendimentosMes ?? 0 }}</p>
    </div>
    <div class="p-4 bg-white rounded-lg shadow">
        <p class="text-sm text-gray-500"><i class="fas fa-dollar-sign mr-2"></i>Comissões (total do mês)</p>
        <p class="mt-2 text-2xl font-semibold text-gray-700">R$ {{ $comissoesMes ?? '0,00' }}</p>
    </div>
</div>
<div class="mb-4">
    <label class="mr-2 text-sm text-gray-700">Clínica:</label>
    <select class="border rounded px-2 py-1" name="clinica">
        <option value="">Todas as Clínicas</option>
        @foreach ($clinicas as $clinica)
            <option value="{{ $clinica->id }}">{{ $clinica->nome }}</option>
        @endforeach
    </select>
</div>
<div class="overflow-x-auto bg-white rounded-lg shadow">
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Profissional</th>
                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Cargo</th>
                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Clínicas</th>
                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Contato</th>
                <th class="px-4 py-2 text-right text-xs font-medium text-gray-500 uppercase">Ações</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-200">
            @forelse($profissionais as $profissional)
                <tr>
                    <td class="px-4 py-2 whitespace-nowrap">{{ $profissional->nome }}</td>
                    <td class="px-4 py-2 whitespace-nowrap">{{ $profissional->cargo }}</td>
                    <td class="px-4 py-2 whitespace-nowrap">
                        @foreach($profissional->clinicas as $clinica)
                            <span class="block">{{ $clinica->nome }}</span>
                        @endforeach
                    </td>
                    <td class="px-4 py-2 whitespace-nowrap">{{ $profissional->contato }}</td>
                    <td class="px-4 py-2 whitespace-nowrap text-right">
                        <a href="{{ route('profissionais.edit', $profissional) }}" class="text-gray-600 hover:text-blue-600" title="Editar">
                            <i class="fas fa-edit"></i>
                        </a>
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
