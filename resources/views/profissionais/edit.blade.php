@extends('layouts.app')

@section('content')
@include('partials.breadcrumbs', ['crumbs' => [
    ['label' => 'Dashboard', 'url' => route('admin.index')],
    ['label' => 'Profissionais', 'url' => route('profissionais.index')],
    ['label' => 'Editar']
]])
<div class="w-full bg-white p-6 rounded-lg shadow" x-data="{ activeTab: 'dados' }">
    <h1 class="text-xl font-semibold mb-4">Editar Profissional</h1>
    <div class="border-b mb-6">
        <nav class="-mb-px flex space-x-4" aria-label="Tabs">
            <button type="button" @click="activeTab = 'dados'" :class="activeTab === 'dados' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'" class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">Dados cadastrais</button>
            <button type="button" @click="activeTab = 'adm'" :class="activeTab === 'adm' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'" class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">Dados admissionais</button>
            <button type="button" @click="activeTab = 'rem'" :class="activeTab === 'rem' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'" class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">Remuneração</button>
            <button type="button" @click="activeTab = 'teste'" :class="activeTab === 'teste' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'" class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">Teste</button>
        </nav>
    </div>
    @if ($errors->any())
        <div class="mb-4">
            <ul class="list-disc list-inside text-sm text-red-600">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <div x-show="activeTab === 'dados'">
    <form method="POST" action="{{ route('profissionais.update', $profissional) }}" enctype="multipart/form-data" class="space-y-6">
        @csrf
        @method('PUT')
        <x-accordion-section title="Dados pessoais" :open="true">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="text-sm font-medium text-gray-700 mb-2 block">Nome</label>
                    <input type="text" name="nome" value="{{ old('nome', $profissional->nome) }}" class="w-full rounded border-[1.5px] border-stroke bg-gray-2 py-3 px-5 text-sm text-black focus:border-primary focus:outline-none" required />
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-700 mb-2 block">Nome do meio</label>
                    <input type="text" name="nome_meio" value="{{ old('nome_meio', $profissional->nome_meio) }}" class="w-full rounded border-[1.5px] border-stroke bg-gray-2 py-3 px-5 text-sm text-black focus:border-primary focus:outline-none" />
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-700 mb-2 block">Último nome</label>
                    <input type="text" name="ultimo_nome" value="{{ old('ultimo_nome', $profissional->ultimo_nome) }}" class="w-full rounded border-[1.5px] border-stroke bg-gray-2 py-3 px-5 text-sm text-black focus:border-primary focus:outline-none" required />
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-700 mb-2 block">Data de nascimento</label>
                    <input type="date" name="data_nascimento" value="{{ old('data_nascimento', optional($profissional->data_nascimento)->format('Y-m-d')) }}" class="w-full rounded border-[1.5px] border-stroke bg-gray-2 py-3 px-5 text-sm text-black focus:border-primary focus:outline-none" />
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-700 mb-2 block">Sexo</label>
                    <select name="sexo" class="w-full rounded border-[1.5px] border-stroke bg-gray-2 py-3 px-5 text-sm text-black focus:border-primary focus:outline-none">
                        <option value="">Selecione</option>
                        <option value="Masculino" @selected(old('sexo', $profissional->sexo)==='Masculino')>Masculino</option>
                        <option value="Feminino" @selected(old('sexo', $profissional->sexo)==='Feminino')>Feminino</option>
                        <option value="Outro" @selected(old('sexo', $profissional->sexo)==='Outro')>Outro</option>
                    </select>
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-700 mb-2 block">Naturalidade</label>
                    <input type="text" name="naturalidade" value="{{ old('naturalidade', $profissional->naturalidade) }}" class="w-full rounded border-[1.5px] border-stroke bg-gray-2 py-3 px-5 text-sm text-black focus:border-primary focus:outline-none" />
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-700 mb-2 block">Nacionalidade</label>
                    <input type="text" name="nacionalidade" value="{{ old('nacionalidade', $profissional->nacionalidade) }}" class="w-full rounded border-[1.5px] border-stroke bg-gray-2 py-3 px-5 text-sm text-black focus:border-primary focus:outline-none" />
                </div>
                <div class="sm:col-span-2">
                    <label class="text-sm font-medium text-gray-700 mb-2 block">Foto</label>
                    <input type="file" name="foto" class="w-full text-sm text-gray-700" />
                </div>
            </div>
        </x-accordion-section>
        <x-accordion-section title="Documentos">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="text-sm font-medium text-gray-700 mb-2 block">CPF</label>
                    <input type="text" name="cpf" value="{{ old('cpf', $profissional->cpf) }}" class="w-full rounded border-[1.5px] border-stroke bg-gray-2 py-3 px-5 text-sm text-black focus:border-primary focus:outline-none" />
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-700 mb-2 block">RG</label>
                    <input type="text" name="rg" value="{{ old('rg', $profissional->rg) }}" class="w-full rounded border-[1.5px] border-stroke bg-gray-2 py-3 px-5 text-sm text-black focus:border-primary focus:outline-none" />
                </div>
            </div>
        </x-accordion-section>
        <x-accordion-section title="Dados de contato">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="text-sm font-medium text-gray-700 mb-2 block">Email</label>
                    <input type="email" name="email" value="{{ old('email', $profissional->email) }}" class="w-full rounded border-[1.5px] border-stroke bg-gray-2 py-3 px-5 text-sm text-black focus:border-primary focus:outline-none" />
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-700 mb-2 block">Telefone</label>
                    <input type="text" name="telefone" value="{{ old('telefone', $profissional->telefone) }}" class="w-full rounded border-[1.5px] border-stroke bg-gray-2 py-3 px-5 text-sm text-black focus:border-primary focus:outline-none" />
                </div>
            </div>
        </x-accordion-section>
        <x-accordion-section title="Endereço">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="text-sm font-medium text-gray-700 mb-2 block">CEP</label>
                    <input type="text" name="cep" value="{{ old('cep', $profissional->cep) }}" class="w-full rounded border-[1.5px] border-stroke bg-gray-2 py-3 px-5 text-sm text-black focus:border-primary focus:outline-none" />
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-700 mb-2 block">Logradouro</label>
                    <input type="text" name="logradouro" value="{{ old('logradouro', $profissional->logradouro) }}" class="w-full rounded border-[1.5px] border-stroke bg-gray-2 py-3 px-5 text-sm text-black focus:border-primary focus:outline-none" />
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-700 mb-2 block">Número</label>
                    <input type="text" name="numero" value="{{ old('numero', $profissional->numero) }}" class="w-full rounded border-[1.5px] border-stroke bg-gray-2 py-3 px-5 text-sm text-black focus:border-primary focus:outline-none" />
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-700 mb-2 block">Complemento</label>
                    <input type="text" name="complemento" value="{{ old('complemento', $profissional->complemento) }}" class="w-full rounded border-[1.5px] border-stroke bg-gray-2 py-3 px-5 text-sm text-black focus:border-primary focus:outline-none" />
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-700 mb-2 block">Bairro</label>
                    <input type="text" name="bairro" value="{{ old('bairro', $profissional->bairro) }}" class="w-full rounded border-[1.5px] border-stroke bg-gray-2 py-3 px-5 text-sm text-black focus:border-primary focus:outline-none" />
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-700 mb-2 block">Cidade</label>
                    <input type="text" name="cidade" value="{{ old('cidade', $profissional->cidade) }}" class="w-full rounded border-[1.5px] border-stroke bg-gray-2 py-3 px-5 text-sm text-black focus:border-primary focus:outline-none" />
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-700 mb-2 block">Estado</label>
                    <input type="text" name="estado" value="{{ old('estado', $profissional->estado) }}" class="w-full rounded border-[1.5px] border-stroke bg-gray-2 py-3 px-5 text-sm text-black focus:border-primary focus:outline-none" />
                </div>
            </div>
        </x-accordion-section>
        <div class="flex justify-between pt-4">
            <a href="{{ route('profissionais.index') }}" class="py-2 px-4 rounded border border-stroke text-gray-700">Cancelar</a>
            <button type="submit" class="py-2 px-4 bg-blue-600 text-white rounded hover:bg-blue-700">Salvar Profissional</button>
        </div>
    </form>
    </div>
    <div x-show="activeTab === 'adm'" x-cloak>
        <p class="text-gray-700">Conteúdo de dados admissionais.</p>
    </div>
    <div x-show="activeTab === 'rem'" x-cloak>
        <p class="text-gray-700">Informações de remuneração.</p>
    </div>
    <div x-show="activeTab === 'teste'" x-cloak>
        <p class="text-gray-700">Conteúdo de teste.</p>
    </div>
</div>
@endsection
