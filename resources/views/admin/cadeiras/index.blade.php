@extends('layouts.app')

@section('content')
@include('partials.breadcrumbs', ['crumbs' => [
    ['label' => 'Dashboard', 'url' => route('admin.index')],
    ['label' => 'Cadeiras']
]])
<div class="mb-4 flex justify-between items-center">
    <h1 class="text-xl font-semibold">Cadeiras</h1>
    <a class="py-2 px-4 bg-blue-600 text-white rounded hover:bg-blue-700" href="{{ route('cadeiras.create') }}">Nova Cadeira</a>
</div>
<div class="overflow-x-auto bg-white rounded-lg shadow">
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Nome</th>
                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Especialidade</th>
                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Ações</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-200">
            @forelse ($cadeiras as $cadeira)
                <tr>
                    <td class="px-4 py-2 whitespace-nowrap">{{ $cadeira->nome }}</td>
                    <td class="px-4 py-2 whitespace-nowrap">{{ $cadeira->especialidade }}</td>
                    <td class="px-4 py-2 whitespace-nowrap">{{ $cadeira->status }}</td>
                    <td class="px-4 py-2 whitespace-nowrap">
                        <a href="{{ route('cadeiras.edit', $cadeira) }}" class="text-blue-600 hover:underline">Editar</a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="px-4 py-2 text-center">Nenhuma cadeira cadastrada.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
