@extends('layouts.app')

@section('content')
@include('partials.breadcrumbs', ['crumbs' => [
    ['label' => 'Backend', 'url' => route('backend.index')],
    ['label' => 'Organizações', 'url' => route('organizacoes.index')],
    ['label' => 'Criar']
]])
<div class="w-full bg-white p-6 rounded-lg shadow">
    <h1 class="text-xl font-semibold mb-4">Criar Organização</h1>
    @if ($errors->any())
        <div class="mb-4">
            <ul class="list-disc list-inside text-sm text-red-600">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <form method="POST" action="{{ route('organizacoes.store') }}" class="space-y-4">
        @csrf
        <div>
            <label class="mb-2 block text-sm font-medium text-gray-700">Nome Fantasia</label>
            <input class="w-full rounded border-[1.5px] border-stroke bg-gray-2 py-3 px-5 text-sm text-black focus:border-primary focus:outline-none" type="text" name="nome_fantasia" value="{{ old('nome_fantasia') }}" required />
        </div>
        <div>
            <label class="mb-2 block text-sm font-medium text-gray-700">Razão Social</label>
            <input class="w-full rounded border-[1.5px] border-stroke bg-gray-2 py-3 px-5 text-sm text-black focus:border-primary focus:outline-none" type="text" name="razao_social" value="{{ old('razao_social') }}" />
        </div>
        <div>
            <label class="mb-2 block text-sm font-medium text-gray-700">CNPJ</label>
            <input class="w-full rounded border-[1.5px] border-stroke bg-gray-2 py-3 px-5 text-sm text-black focus:border-primary focus:outline-none" type="text" name="cnpj" value="{{ old('cnpj') }}" required />
        </div>
        <div>
            <label class="mb-2 block text-sm font-medium text-gray-700">Email</label>
            <input class="w-full rounded border-[1.5px] border-stroke bg-gray-2 py-3 px-5 text-sm text-black focus:border-primary focus:outline-none" type="email" name="email" value="{{ old('email') }}" required />
        </div>
        <div>
            <label class="mb-2 block text-sm font-medium text-gray-700">Telefone</label>
            <input class="w-full rounded border-[1.5px] border-stroke bg-gray-2 py-3 px-5 text-sm text-black focus:border-primary focus:outline-none" type="text" name="telefone" value="{{ old('telefone') }}" />
        </div>
        <div>
            <label class="mb-2 block text-sm font-medium text-gray-700">Status</label>
            <select name="status" class="w-full rounded border-[1.5px] border-stroke bg-gray-2 py-3 px-5 text-sm text-black focus:border-primary focus:outline-none">
                <option value="ativo" @selected(old('status') === 'ativo')>Ativa</option>
                <option value="inativo" @selected(old('status') === 'inativo')>Inativa</option>
            </select>
        </div>
        <div>
            <label class="mb-2 block text-sm font-medium text-gray-700">Responsável</label>
            <input class="w-full rounded border-[1.5px] border-stroke bg-gray-2 py-3 px-5 text-sm text-black focus:border-primary focus:outline-none" type="text" name="responsavel" value="{{ old('responsavel') }}" required />
        </div>
        <div>
            <label class="mb-2 block text-sm font-medium text-gray-700">Senha (opcional)</label>
            <input class="w-full rounded border-[1.5px] border-stroke bg-gray-2 py-3 px-5 text-sm text-black focus:border-primary focus:outline-none" type="password" name="password" />
            <p class="text-xs text-gray-500 mt-1">Se deixado em branco, uma senha aleatória será criada e enviada por e-mail.</p>
        </div>
        <button type="submit" class="py-2 px-4 bg-blue-600 text-white rounded hover:bg-blue-700">Salvar</button>
    </form>
</div>
@endsection
