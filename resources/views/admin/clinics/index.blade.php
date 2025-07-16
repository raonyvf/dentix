@extends('layouts.app')

@section('content')
@include('partials.breadcrumbs', ['crumbs' => [
    ['label' => 'Dashboard', 'url' => route('admin.index')],
    ['label' => 'Clínicas']
]])
<div class="mb-4 flex justify-between items-center">
    <h1 class="text-xl font-semibold">Clínicas</h1>
    <a class="py-2 px-4 bg-blue-600 text-white rounded hover:bg-blue-700" href="{{ route('clinicas.create') }}">Nova Clínica</a>
</div>
<div class="overflow-x-auto bg-white rounded-lg shadow">
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Nome</th>
                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">CNPJ</th>
                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Responsável</th>
                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Ações</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-200">
            @forelse ($clinics as $clinic)
                <tr>
                    <td class="px-4 py-2 whitespace-nowrap">{{ $clinic->nome }}</td>
                    <td class="px-4 py-2 whitespace-nowrap">{{ $clinic->cnpj }}</td>
                    <td class="px-4 py-2 whitespace-nowrap">{{ $clinic->responsavel }}</td>
                    <td class="px-4 py-2 whitespace-nowrap">
                        <a href="{{ route('clinicas.edit', $clinic) }}" class="text-blue-600 hover:underline">Editar</a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="px-4 py-2 text-center">Nenhuma clínica cadastrada.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
