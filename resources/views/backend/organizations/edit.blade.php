@extends('layouts.app')

@section('content')
@include('partials.breadcrumbs', ['crumbs' => [
    ['label' => 'Backend', 'url' => route('backend.index')],
    ['label' => 'Organizações', 'url' => route('organizacoes.index')],
    ['label' => 'Editar']
]])
<div class="w-full bg-white p-6 rounded-lg shadow">
    <h1 class="text-xl font-semibold mb-4">Editar Organização</h1>
    @if ($errors->any())
        <div class="mb-4">
            <ul class="list-disc list-inside text-sm text-red-600">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <form method="POST" action="{{ route('organizacoes.update', $organization) }}" class="space-y-6">
        @csrf
        @method('PUT')
        <div class="rounded-sm border border-stroke bg-gray-50 p-4">
            <h2 class="mb-4 text-sm font-medium text-gray-700">Dados da Organização</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="mb-2 block text-sm font-medium text-gray-700">Nome</label>
                    <input class="w-full rounded border-[1.5px] border-stroke bg-gray-2 py-3 px-5 text-sm text-black focus:border-primary focus:outline-none" type="text" name="first_name" value="{{ old('first_name', $organization->responsavel_first_name) }}" />
                </div>
                <div>
                    <label class="mb-2 block text-sm font-medium text-gray-700">Nome do meio</label>
                    <input class="w-full rounded border-[1.5px] border-stroke bg-gray-2 py-3 px-5 text-sm text-black focus:border-primary focus:outline-none" type="text" name="middle_name" value="{{ old('middle_name', $organization->responsavel_middle_name) }}" />
                </div>
                <div>
                    <label class="mb-2 block text-sm font-medium text-gray-700">Sobrenome</label>
                    <input class="w-full rounded border-[1.5px] border-stroke bg-gray-2 py-3 px-5 text-sm text-black focus:border-primary focus:outline-none" type="text" name="last_name" value="{{ old('last_name', $organization->responsavel_last_name) }}" />
                </div>
                <div class="sm:col-span-2">
                    <label class="mb-2 block text-sm font-medium text-gray-700">Nome Fantasia</label>
                    <input class="w-full rounded border-[1.5px] border-stroke bg-gray-2 py-3 px-5 text-sm text-black focus:border-primary focus:outline-none" type="text" name="nome_fantasia" value="{{ old('nome_fantasia', $organization->nome_fantasia) }}" required />
                </div>
                <div class="sm:col-span-2">
                    <label class="mb-2 block text-sm font-medium text-gray-700">Razão Social</label>
                    <input class="w-full rounded border-[1.5px] border-stroke bg-gray-2 py-3 px-5 text-sm text-black focus:border-primary focus:outline-none" type="text" name="razao_social" value="{{ old('razao_social', $organization->razao_social) }}" />
                </div>
                <div class="sm:col-span-2">
                    <label class="mb-2 block text-sm font-medium text-gray-700">CNPJ</label>
                    <input class="w-full rounded border-[1.5px] border-stroke bg-gray-2 py-3 px-5 text-sm text-black focus:border-primary focus:outline-none" type="text" name="cnpj" value="{{ old('cnpj', $organization->cnpj) }}" required />
                </div>
            </div>
        </div>
        <div class="rounded-sm border border-stroke bg-gray-50 p-4">
            <h2 class="mb-4 text-sm font-medium text-gray-700">Contato</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="mb-2 block text-sm font-medium text-gray-700">Email</label>
                    <input class="w-full rounded border-[1.5px] border-stroke bg-gray-2 py-3 px-5 text-sm text-black focus:border-primary focus:outline-none" type="email" name="email" value="{{ old('email', $organization->email) }}" required />
                </div>
                <div>
                    <label class="mb-2 block text-sm font-medium text-gray-700">Telefone</label>
                    <input class="w-full rounded border-[1.5px] border-stroke bg-gray-2 py-3 px-5 text-sm text-black focus:border-primary focus:outline-none" type="text" name="telefone" value="{{ old('telefone', $organization->telefone) }}" />
                </div>
            </div>
        </div>
        <div class="rounded-sm border border-stroke bg-gray-50 p-4">
            <h2 class="mb-4 text-sm font-medium text-gray-700">Endereço de Faturamento</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="mb-2 block text-sm font-medium text-gray-700">CEP</label>
                    <input class="w-full rounded border-[1.5px] border-stroke bg-gray-2 py-3 px-5 text-sm text-black focus:border-primary focus:outline-none" type="text" name="cep" value="{{ old('cep', $organization->cep) }}" />
                </div>
                <div class="sm:col-span-2">
                    <label class="mb-2 block text-sm font-medium text-gray-700">Logradouro</label>
                    <input class="w-full rounded border-[1.5px] border-stroke bg-gray-2 py-3 px-5 text-sm text-black focus:border-primary focus:outline-none" type="text" name="logradouro" value="{{ old('logradouro', $organization->logradouro) }}" />
                </div>
                <div>
                    <label class="mb-2 block text-sm font-medium text-gray-700">Número</label>
                    <input class="w-full rounded border-[1.5px] border-stroke bg-gray-2 py-3 px-5 text-sm text-black focus:border-primary focus:outline-none" type="text" name="numero" value="{{ old('numero', $organization->numero) }}" />
                </div>
                <div>
                    <label class="mb-2 block text-sm font-medium text-gray-700">Complemento</label>
                    <input class="w-full rounded border-[1.5px] border-stroke bg-gray-2 py-3 px-5 text-sm text-black focus:border-primary focus:outline-none" type="text" name="complemento" value="{{ old('complemento', $organization->complemento) }}" />
                </div>
                <div>
                    <label class="mb-2 block text-sm font-medium text-gray-700">Bairro</label>
                    <input class="w-full rounded border-[1.5px] border-stroke bg-gray-2 py-3 px-5 text-sm text-black focus:border-primary focus:outline-none" type="text" name="bairro" value="{{ old('bairro', $organization->bairro) }}" />
                </div>
                <div>
                    <label class="mb-2 block text-sm font-medium text-gray-700">Cidade</label>
                    <input class="w-full rounded border-[1.5px] border-stroke bg-gray-2 py-3 px-5 text-sm text-black focus:border-primary focus:outline-none" type="text" name="cidade" value="{{ old('cidade', $organization->cidade) }}" />
                </div>
                <div>
                    <label class="mb-2 block text-sm font-medium text-gray-700">Estado</label>
                    <input class="w-full rounded border-[1.5px] border-stroke bg-gray-2 py-3 px-5 text-sm text-black focus:border-primary focus:outline-none" type="text" name="estado" value="{{ old('estado', $organization->estado) }}" />
                </div>
            </div>
        </div>
        <div class="rounded-sm border border-stroke bg-gray-50 p-4">
            <h2 class="mb-4 text-sm font-medium text-gray-700">Senha</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="mb-2 block text-sm font-medium text-gray-700">Senha (opcional)</label>
                    <input class="w-full rounded border-[1.5px] border-stroke bg-gray-2 py-3 px-5 text-sm text-black focus:border-primary focus:outline-none" type="password" name="password" />
                </div>
                <div>
                    <label class="mb-2 block text-sm font-medium text-gray-700">Confirmar Senha</label>
                    <input class="w-full rounded border-[1.5px] border-stroke bg-gray-2 py-3 px-5 text-sm text-black focus:border-primary focus:outline-none" type="password" name="password_confirmation" />
                </div>
            </div>
            <p class="text-xs text-gray-500 mt-1">Se deixado em branco, a senha permanecerá inalterada.</p>
        </div>
        <div>
            <label class="mb-2 block text-sm font-medium text-gray-700">Status</label>
            <select name="status" class="w-full rounded border-[1.5px] border-stroke bg-gray-2 py-3 px-5 text-sm text-black focus:border-primary focus:outline-none">
                <option value="ativo" @selected(old('status', $organization->status) === 'ativo')>Ativa</option>
                <option value="inativo" @selected(old('status', $organization->status) === 'inativo')>Inativa</option>
            </select>
        </div>
        <div class="flex justify-end pt-4">
            <button type="submit" class="py-2 px-4 bg-blue-600 text-white rounded hover:bg-blue-700">Salvar</button>
        </div>
    </form>
</div>
@endsection
