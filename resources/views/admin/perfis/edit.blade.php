@extends('layouts.app')

@section('content')
@include('partials.breadcrumbs', ['crumbs' => [
    ['label' => 'Dashboard', 'url' => route('admin.index')],
    ['label' => 'Perfis', 'url' => route('perfis.index')],
    ['label' => 'Editar']
]])
<div class="w-full bg-white p-6 rounded-lg shadow">
    <h1 class="text-xl font-semibold mb-4">Editar Perfil</h1>
    <form method="POST" action="{{ route('perfis.update', $perfil) }}" class="space-y-4">
        @csrf
        @method('PUT')
        <div>
            <label class="mb-2 block text-sm font-medium text-gray-700">Nome</label>
            <input class="w-full rounded border-[1.5px] border-stroke bg-gray-2 py-3 px-5 text-sm text-black focus:border-primary focus:outline-none" type="text" name="nome" value="{{ $perfil->nome }}" required />
        </div>
        <div class="overflow-x-auto bg-white rounded-lg shadow">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Módulo</th>
                        <th class="px-4 py-2 text-center text-xs font-medium text-gray-500 uppercase">Leitura</th>
                        <th class="px-4 py-2 text-center text-xs font-medium text-gray-500 uppercase">Escrita</th>
                        <th class="px-4 py-2 text-center text-xs font-medium text-gray-500 uppercase">Atualização</th>
                        <th class="px-4 py-2 text-center text-xs font-medium text-gray-500 uppercase">Exclusão</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach($modules as $module)
                    <tr>
                        <td class="px-4 py-2">{{ $module }}</td>
                        <td class="px-4 py-2 text-center"><input type="checkbox" name="permissoes[{{ $module }}][leitura]" class="rounded border-gray-300 text-primary focus:ring-primary" @checked(optional($permissoes->get($module))->leitura)></td>
                        <td class="px-4 py-2 text-center"><input type="checkbox" name="permissoes[{{ $module }}][escrita]" class="rounded border-gray-300 text-primary focus:ring-primary" @checked(optional($permissoes->get($module))->escrita)></td>
                        <td class="px-4 py-2 text-center"><input type="checkbox" name="permissoes[{{ $module }}][atualizacao]" class="rounded border-gray-300 text-primary focus:ring-primary" @checked(optional($permissoes->get($module))->atualizacao)></td>
                        <td class="px-4 py-2 text-center"><input type="checkbox" name="permissoes[{{ $module }}][exclusao]" class="rounded border-gray-300 text-primary focus:ring-primary" @checked(optional($permissoes->get($module))->exclusao)></td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <button type="submit" class="py-2 px-4 bg-blue-600 text-white rounded hover:bg-blue-700">Salvar</button>
    </form>
</div>
@endsection
