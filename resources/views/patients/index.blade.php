@extends('layouts.app')

@section('content')
@include('partials.breadcrumbs', ['crumbs' => [
    ['label' => 'Dashboard', 'url' => route('admin.index')],
    ['label' => 'Pacientes']
]])
<div class="mb-4 flex justify-between items-center">
    <h1 class="text-xl font-semibold">Pacientes</h1>
    <div class="flex gap-2 items-center">
        <form method="GET" class="relative">
            <input type="text" name="q" value="{{ request('q') }}" placeholder="Buscar..." class="pl-8 pr-3 py-2 border rounded-lg bg-gray-100 focus:border-primary focus:ring-0" />
            <span class="absolute inset-y-0 left-0 flex items-center pl-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35m0 0A7.5 7.5 0 103 10.5a7.5 7.5 0 0013.65 6.15z" />
                </svg>
            </span>
        </form>
        <a class="py-2 px-4 bg-blue-600 text-white rounded hover:bg-blue-700" href="{{ route('pacientes.create') }}">+ Novo Paciente</a>
    </div>
</div>

<div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3 mb-6">
    <div class="p-4 bg-white rounded-lg shadow">
        <p class="text-sm text-gray-500">Total de Pacientes</p>
        <p class="mt-2 text-2xl font-semibold text-gray-700">{{ $total }}</p>
        <p class="text-xs text-gray-500 mt-1">Variação 30d: {{ number_format($variation, 1) }}%</p>
    </div>
    <div class="p-4 bg-white rounded-lg shadow">
        <p class="text-sm text-gray-500">Consultas Agendadas</p>
        <p class="mt-2 text-2xl font-semibold text-gray-700">{{ $scheduledAppointments }}</p>
    </div>
    <div class="p-4 bg-white rounded-lg shadow">
        <p class="text-sm text-gray-500">Retornos Pendentes</p>
        <p class="mt-2 text-2xl font-semibold text-gray-700">{{ $pendingReturns }}</p>
    </div>
</div>

<div class="overflow-x-auto bg-white rounded-lg shadow">
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Nome</th>
                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Responsável</th>
                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Idade</th>
                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Telefone</th>
                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Última Consulta</th>
                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Próxima Consulta</th>
                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Ações</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-200">
            @forelse ($patients as $patient)
                <tr>
                    <td class="px-4 py-2 whitespace-nowrap">{{ $patient->nome }}</td>
                    <td class="px-4 py-2 whitespace-nowrap">{{ $patient->responsavel }}</td>
                    <td class="px-4 py-2 whitespace-nowrap">{{ $patient->idade }}</td>
                    <td class="px-4 py-2 whitespace-nowrap">{{ $patient->telefone }}</td>
                    <td class="px-4 py-2 whitespace-nowrap">{{ optional($patient->ultima_consulta)->format('d/m/Y') }}</td>
                    <td class="px-4 py-2 whitespace-nowrap">{{ optional($patient->proxima_consulta)->format('d/m/Y') }}</td>
                    <td class="px-4 py-2 whitespace-nowrap">
                        <a href="{{ route('pacientes.edit', $patient) }}" class="text-blue-600 hover:underline">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-1.5a2.121 2.121 0 113 3L9 21H4v-5l12.732-12.732z" />
                            </svg>
                        </a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="px-4 py-2 text-center">Nenhum paciente encontrado.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
