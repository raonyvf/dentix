@extends('layouts.app')

@section('content')
@include('partials.breadcrumbs', ['crumbs' => [
    ['label' => 'Dashboard', 'url' => route('admin.index')],
    ['label' => 'Profissionais']
]])
<div class="mb-4 flex justify-between items-center">
    <h1 class="text-xl font-semibold">Profissionais</h1>
    <a class="py-2 px-4 bg-blue-600 text-white rounded hover:bg-blue-700" href="{{ route('profissionais.create') }}">Novo Profissional</a>
</div>
<div class="overflow-x-auto bg-white rounded-lg shadow">
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Nome</th>
                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Email</th>
                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Telefone</th>
                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Perfis/Clínicas</th>
                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Ações</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-200">
            @forelse ($users as $user)
                <tr>
                    <td class="px-4 py-2 whitespace-nowrap">{{ $user->name }}</td>
                    <td class="px-4 py-2 whitespace-nowrap">{{ $user->email }}</td>
                    <td class="px-4 py-2 whitespace-nowrap">{{ $user->phone }}</td>
                    <td class="px-4 py-2 whitespace-nowrap">
                        @foreach ($user->clinics as $clinic)
                            @php $p = \App\Models\Profile::find($clinic->pivot->profile_id); @endphp
                            <div>{{ $clinic->nome }} - {{ optional($p)->nome }}</div>
                        @endforeach
                    </td>
                    <td class="px-4 py-2 whitespace-nowrap">
                        <a href="{{ route('profissionais.edit', $user) }}" class="text-blue-600 hover:underline">Editar</a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="px-4 py-2 text-center">Nenhum usuário cadastrado.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
