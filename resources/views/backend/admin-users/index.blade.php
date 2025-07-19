@extends('layouts.app')

@section('content')
@include('partials.breadcrumbs', ['crumbs' => [
    ['label' => 'Backend', 'url' => route('backend.index')],
    ['label' => 'Usuários Admin']
]])
<div class="mb-4 flex justify-between items-center">
    <h1 class="text-xl font-semibold">Usuários Admin</h1>
</div>
<div class="overflow-x-auto bg-white rounded-lg shadow">
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Nome</th>
                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Email</th>
                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Organização</th>
                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Ações</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-200">
            @forelse ($admins as $admin)
                <tr>
                    <td class="px-4 py-2 whitespace-nowrap">{{ $admin->name }}</td>
                    <td class="px-4 py-2 whitespace-nowrap">{{ $admin->email }}</td>
                    <td class="px-4 py-2 whitespace-nowrap">{{ $admin->organization->nome_fantasia ?? 'N/A' }}</td>
                    <td class="px-4 py-2 whitespace-nowrap">
                        <a href="{{ route('usuarios-admin.edit', $admin) }}" class="text-blue-600 hover:underline">Editar Senha</a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="px-4 py-2 text-center">Nenhum administrador encontrado.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
