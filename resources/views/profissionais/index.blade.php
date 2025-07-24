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
                <td class="px-4 py-2 whitespace-nowrap">
                    {{ optional($profissional->user->person)->first_name ?? $profissional->person->first_name }}
                    {{ optional($profissional->user->person)->last_name ?? $profissional->person->last_name }}
                </td>
                <td class="px-4 py-2 whitespace-nowrap">
                    {{ optional($profissional->user->person)->email ?? $profissional->person->email }}
                </td>
                <td class="px-4 py-2 whitespace-nowrap">
                    {{ optional($profissional->user->person)->phone ?? $profissional->person->phone }}
                </td>
                <td class="px-4 py-2 whitespace-nowrap">
                    <a href="{{ route('profissionais.show', $profissional) }}" class="text-gray-600 hover:text-blue-600 mr-2" title="Ver">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 inline" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                    </a>
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
