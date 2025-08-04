@extends('layouts.app')

@section('content')
@include('partials.breadcrumbs', ['crumbs' => [
    ['label' => 'Dashboard', 'url' => route('admin.index')],
    ['label' => 'Usuários']
]])
<div class="mb-4">
    <h1 class="text-xl font-semibold">Usuários</h1>
</div>
<div class="overflow-x-auto bg-white rounded-lg shadow">
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Nome</th>
                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Email</th>
                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Perfis/Clínicas</th>
                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Ações</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-200">
            @forelse ($users as $user)
                <tr>
                    <td class="px-4 py-2 whitespace-nowrap">
                        @if($user->pessoa)
                            {{ $user->pessoa->full_name }}
                        @else
                            {{ $user->name ?? '-' }}
                        @endif
                    </td>
                    <td class="px-4 py-2 whitespace-nowrap">{{ $user->email }}</td>
                    <td class="px-4 py-2 whitespace-nowrap">
                        @foreach ($user->clinics as $clinic)
                            @php $p = \App\Models\Perfil::find($clinic->pivot->perfil_id); @endphp
                            <div>{{ $clinic->nome }} - {{ optional($p)->nome }}</div>
                        @endforeach
                    </td>
                    <td class="px-4 py-2 whitespace-nowrap">
                        <a href="{{ route('usuarios.edit', $user) }}" class="text-blue-600 hover:underline">Editar</a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="px-4 py-2 text-center">Nenhum usuário cadastrado.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
