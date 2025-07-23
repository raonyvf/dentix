@extends('layouts.app')

@section('content')
@include('partials.breadcrumbs', ['crumbs' => [
    ['label' => 'Dashboard', 'url' => route('admin.index')],
    ['label' => 'Profissionais']
]])
<div class="mb-6 flex justify-between items-start">
    <div>
        <h1 class="text-2xl font-bold">Profissionais</h1>
        <p class="text-gray-600">Gerencie todos os profissionais da clínica</p>
    </div>
    <a href="{{ route('profissionais.create') }}" class="py-2 px-4 bg-blue-600 text-white rounded hover:bg-blue-700">+ Novo Profissional</a>
</div>
<div class="bg-white rounded-lg shadow overflow-x-auto">
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Nome</th>
                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Email</th>
                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Telefone</th>
                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Ações</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-200">
            @forelse($profissionais as $profissional)
            <tr>
                <td class="px-4 py-2 whitespace-nowrap">{{ $profissional->nome }} {{ $profissional->ultimo_nome }}</td>
                <td class="px-4 py-2 whitespace-nowrap">{{ $profissional->email }}</td>
                <td class="px-4 py-2 whitespace-nowrap">{{ $profissional->telefone }}</td>
                <td class="px-4 py-2 whitespace-nowrap">
                    <a href="{{ route('profissionais.edit', $profissional) }}" class="text-blue-600 hover:underline">Editar</a>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="4" class="px-4 py-2 text-center">Nenhum profissional cadastrado.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
