@extends('layouts.app')

@section('content')
<div class="mb-4 flex justify-between items-center">
    <h1 class="text-xl font-semibold">Unidades</h1>
    <a class="py-2 px-4 bg-blue-600 text-white rounded hover:bg-blue-700" href="{{ route('unidades.create') }}">Nova Unidade</a>
</div>
<div class="overflow-x-auto bg-white rounded-lg shadow">
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Nome</th>
                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Cidade</th>
                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Estado</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-200">
            @forelse ($unidades as $unidade)
                <tr>
                    <td class="px-4 py-2 whitespace-nowrap">{{ $unidade->nome }}</td>
                    <td class="px-4 py-2 whitespace-nowrap">{{ $unidade->cidade }}</td>
                    <td class="px-4 py-2 whitespace-nowrap">{{ $unidade->estado }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="3" class="px-4 py-2 text-center">Nenhuma unidade cadastrada.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
