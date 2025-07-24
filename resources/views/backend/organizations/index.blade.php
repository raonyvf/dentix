@extends('layouts.app')

@section('content')
@include('partials.breadcrumbs', ['crumbs' => [
    ['label' => 'Backend', 'url' => route('backend.index')],
    ['label' => 'Organizações']
]])
<div class="mb-4 flex justify-between items-center">
    <h1 class="text-xl font-semibold">Organizações</h1>
    <a class="py-2 px-4 bg-blue-600 text-white rounded hover:bg-blue-700" href="{{ route('organizacoes.create') }}">Nova Organização</a>
</div>
<div class="overflow-x-auto bg-white rounded-lg shadow">
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Nome Fantasia</th>
                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">CNPJ</th>
                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Responsável</th>
                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Ações</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-200">
            @forelse ($organizations as $organization)
                <tr>
                    <td class="px-4 py-2 whitespace-nowrap">{{ $organization->nome_fantasia }}</td>
                    <td class="px-4 py-2 whitespace-nowrap">{{ $organization->cnpj }}</td>
                    <td class="px-4 py-2 whitespace-nowrap">
                        {{ trim($organization->responsavel_first_name . ' ' . ($organization->responsavel_middle_name ? $organization->responsavel_middle_name . ' ' : '') . $organization->responsavel_last_name) }}
                    </td>
                    <td class="px-4 py-2 whitespace-nowrap">{{ $organization->status }}</td>
                    <td class="px-4 py-2 whitespace-nowrap">
                        <a href="{{ route('organizacoes.edit', $organization) }}" class="text-blue-600 hover:underline">Editar</a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="px-4 py-2 text-center">Nenhuma organização cadastrada.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
