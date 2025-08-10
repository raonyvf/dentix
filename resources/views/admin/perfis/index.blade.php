@extends('layouts.app')

@section('content')
@include('partials.breadcrumbs', ['crumbs' => [
    ['label' => 'Dashboard', 'url' => route('admin.index')],
    ['label' => 'Perfis']
]])
<div class="mb-4 flex justify-between items-center">
    <h1 class="text-xl font-semibold">Perfis</h1>
    <a class="py-2 px-4 bg-blue-600 text-white rounded hover:bg-blue-700" href="{{ route('perfis.create') }}">Novo Perfil</a>
</div>
<div class="overflow-x-auto bg-white rounded-lg shadow">
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Nome</th>
                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Ações</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-200">
            @forelse ($perfis as $perfil)
                <tr>
                    <td class="px-4 py-2 whitespace-nowrap">{{ $perfil->nome }}</td>
                    <td class="px-4 py-2 whitespace-nowrap">
                        <a href="{{ route('perfis.edit', $perfil) }}" class="text-blue-600 hover:underline">Editar</a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="2" class="px-4 py-2 text-center">Nenhum perfil cadastrado.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
