@extends('layouts.app')

@section('content')
@include('partials.breadcrumbs', ['crumbs' => [
    ['label' => 'Dashboard', 'url' => route('admin.index')],
    ['label' => 'Pacientes']
]])
<div class="mb-6 flex items-start justify-between">
    <div>
        <h1 class="text-2xl font-bold">Pacientes</h1>
        <p class="text-gray-600">Gerencie todos os pacientes da clínica</p>
    </div>
    <a href="{{ route('pacientes.create') }}" class="py-2 px-4 bg-blue-600 text-white rounded hover:bg-blue-700 flex items-center">
        + Novo Paciente
    </a>
</div>
<div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
    <div class="p-4 bg-white rounded-lg shadow">
        <p class="text-sm text-gray-500">Total de Pacientes</p>
        <p class="mt-2 text-3xl font-semibold text-gray-700">2.834</p>
        <p class="text-xs text-gray-500">+180 nos últimos 30 dias</p>
    </div>
    <div class="p-4 bg-white rounded-lg shadow">
        <p class="text-sm text-gray-500">Consultas Agendadas</p>
        <p class="mt-2 text-2xl font-semibold text-gray-700">148</p>
        <p class="text-xs text-gray-500">Próximos 30 dias</p>
    </div>
    <div class="p-4 bg-white rounded-lg shadow">
        <p class="text-sm text-gray-500">Retornos Pendentes</p>
        <p class="mt-2 text-2xl font-semibold text-gray-700">29</p>
        <p class="text-xs text-gray-500">Necessitam reagendamento</p>
    </div>
</div>
<div class="bg-white rounded-lg shadow">
    <div class="flex items-center justify-between px-4 pt-4">
        <h2 class="text-lg font-semibold">Lista de Pacientes</h2>
        <input type="text" placeholder="Buscar paciente..." class="border rounded-lg px-3 py-2 text-sm" />
    </div>
    <div class="overflow-x-auto">
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
                @foreach ($pacientes as $paciente)
                    <tr>
                        <td class="px-4 py-2 whitespace-nowrap">{{ $paciente->nome }} {{ $paciente->ultimo_nome }}</td>
                        <td class="px-4 py-2 whitespace-nowrap">{{ $paciente->responsavel_nome ?? '-' }}</td>
                        <td class="px-4 py-2 whitespace-nowrap">{{ \Carbon\Carbon::parse($paciente->data_nascimento)->age }}</td>
                        <td class="px-4 py-2 whitespace-nowrap">{{ $paciente->telefone }}</td>
                        <td class="px-4 py-2 whitespace-nowrap">-</td>
                        <td class="px-4 py-2 whitespace-nowrap">-</td>
                        <td class="px-4 py-2 whitespace-nowrap text-center">
                            <a href="#" class="text-blue-600 hover:underline" title="Ver Prontuário">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 inline" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.105 0-2 .895-2 2s.895 2 2 2 2-.895 2-2-.895-2-2-2z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14c-4.418 0-8 2.239-8 5v3h16v-3c0-2.761-3.582-5-8-5z" />
                                </svg>
                            </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection

