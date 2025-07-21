@extends('layouts.app')

@section('content')
@include('partials.breadcrumbs', ['crumbs' => [
    ['label' => 'Dashboard', 'url' => route('admin.index')],
    ['label' => 'Orçamentos']
]])
@php
    $orcamentos = [
        ['id' => 101, 'data' => '01/04/2024', 'valor' => 'R$ 7.650,00', 'status' => 'Aprovado', 'profissional' => 'Dr. Paulo'],
        ['id' => 102, 'data' => '15/05/2024', 'valor' => 'R$ 3.200,00', 'status' => 'Pendente', 'profissional' => 'Dra. Ana'],
        ['id' => 103, 'data' => '20/05/2024', 'valor' => 'R$ 4.500,00', 'status' => 'Recusado', 'profissional' => 'Dr. João'],
    ];
    $statusColors = [
        'Aprovado' => 'bg-emerald-100 text-emerald-800',
        'Pendente' => 'bg-orange-100 text-orange-800',
        'Recusado' => 'bg-red-100 text-red-800',
    ];
@endphp
<div class="mb-6">
    <h1 class="text-2xl font-bold">Orçamentos</h1>
    <p class="text-gray-600">Lista de orçamentos cadastrados</p>
</div>
<div class="space-y-4 bg-white rounded-lg shadow p-4">
    <div>
        <div class="flex flex-col sm:flex-row sm:items-end gap-4">
            <select class="w-full sm:w-40 rounded border-stroke bg-gray-2 py-2 px-3 text-sm text-black focus:border-primary focus:outline-none">
                <option value="">Todos</option>
                <option value="Aprovado">Aprovado</option>
                <option value="Pendente">Pendente</option>
                <option value="Recusado">Recusado</option>
            </select>
            <input type="date" class="rounded border-stroke bg-gray-2 py-2 px-3 text-sm text-black focus:border-primary focus:outline-none" />
        </div>
    </div>
    <x-financeiro.table :headings="['ID', 'Data', 'Valor total', 'Status', 'Profissional', 'Ações']">
        @foreach ($orcamentos as $orcamento)
            <tr>
                <td class="px-4 py-2">{{ $orcamento['id'] }}</td>
                <td class="px-4 py-2">{{ $orcamento['data'] }}</td>
                <td class="px-4 py-2">{{ $orcamento['valor'] }}</td>
                <td class="px-4 py-2">
                    <span class="px-2 py-1 rounded-full text-xs {{ $statusColors[$orcamento['status']] ?? '' }}">{{ $orcamento['status'] }}</span>
                </td>
                <td class="px-4 py-2">{{ $orcamento['profissional'] }}</td>
                <td class="px-4 py-2">
                    <a href="{{ route('orcamentos.assinar') }}" class="text-gray-600 hover:text-blue-600" title="Visualizar">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 inline" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                    </a>
                </td>
            </tr>
        @endforeach
    </x-financeiro.table>
</div>
@endsection
