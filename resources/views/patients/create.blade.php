@extends('layouts.app')

@section('content')
@include('partials.breadcrumbs', ['crumbs' => [
    ['label' => 'Dashboard', 'url' => route('admin.index')],
    ['label' => 'Pacientes', 'url' => route('pacientes.index')],
    ['label' => 'Novo']
]])
<div x-data="{ menor: false }" class="w-full bg-white p-6 rounded-lg shadow">
    <h1 class="text-xl font-semibold mb-4">Novo Paciente</h1>


    @if ($errors->any())
        <div class="mb-4">
            <ul class="list-disc list-inside text-sm text-red-600">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('pacientes.store') }}" class="space-y-6">
        @csrf

        <div>
            <div class="rounded-sm border border-stroke bg-gray-50 p-4 mb-4">
                <h2 class="mb-4 text-sm font-medium text-gray-700">Informações Básicas</h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="sm:col-span-2">
                        <label class="mb-2 block text-sm font-medium text-gray-700">Nome completo</label>
                        <input type="text" name="nome" value="{{ old('nome') }}" required class="w-full rounded border-[1.5px] border-stroke bg-gray-2 py-3 px-5 text-sm text-black focus:border-primary focus:outline-none" />
                    </div>
                    <div>
                        <label class="mb-2 block text-sm font-medium text-gray-700">Data de nascimento</label>
                        <input type="date" name="data_nascimento" value="{{ old('data_nascimento') }}" class="w-full rounded border-[1.5px] border-stroke bg-gray-2 py-3 px-5 text-sm text-black focus:border-primary focus:outline-none" />
                    </div>
                    <div>
                        <label class="mb-2 block text-sm font-medium text-gray-700">CPF</label>
                        <input type="text" name="cpf" value="{{ old('cpf') }}" required class="w-full rounded border-[1.5px] border-stroke bg-gray-2 py-3 px-5 text-sm text-black focus:border-primary focus:outline-none" />
                    </div>
                    <div>
                        <label class="mb-2 block text-sm font-medium text-gray-700">Menor de idade?</label>
                        <select name="menor_idade" x-model="menor" class="w-full rounded border-[1.5px] border-stroke bg-gray-2 py-3 px-5 text-sm text-black focus:border-primary focus:outline-none">
                            <option value="0">Não</option>
                            <option value="1">Sim</option>
                        </select>
                    </div>
                    <div class="sm:col-span-2" x-show="menor == 1" x-cloak>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="mb-2 block text-sm font-medium text-gray-700">Nome do responsável</label>
                                <input type="text" name="responsavel_nome" value="{{ old('responsavel_nome') }}" class="w-full rounded border-[1.5px] border-stroke bg-gray-2 py-3 px-5 text-sm text-black focus:border-primary focus:outline-none" />
                            </div>
                            <div>
                                <label class="mb-2 block text-sm font-medium text-gray-700">CPF do responsável</label>
                                <input type="text" name="responsavel_cpf" value="{{ old('responsavel_cpf') }}" x-bind:required="menor == 1" class="w-full rounded border-[1.5px] border-stroke bg-gray-2 py-3 px-5 text-sm text-black focus:border-primary focus:outline-none" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="rounded-sm border border-stroke bg-gray-50 p-4">
                <h2 class="mb-4 text-sm font-medium text-gray-700">Contato</h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="mb-2 block text-sm font-medium text-gray-700">Telefone</label>
                        <input type="text" name="telefone" value="{{ old('telefone') }}" class="w-full rounded border-[1.5px] border-stroke bg-gray-2 py-3 px-5 text-sm text-black focus:border-primary focus:outline-none" />
                    </div>
                    <div>
                        <label class="mb-2 block text-sm font-medium text-gray-700">E-mail</label>
                        <input type="email" name="email" value="{{ old('email') }}" class="w-full rounded border-[1.5px] border-stroke bg-gray-2 py-3 px-5 text-sm text-black focus:border-primary focus:outline-none" />
                    </div>
                    <div>
                        <label class="mb-2 block text-sm font-medium text-gray-700">CEP</label>
                        <input type="text" name="cep" value="{{ old('cep') }}" class="w-full rounded border-[1.5px] border-stroke bg-gray-2 py-3 px-5 text-sm text-black focus:border-primary focus:outline-none" />
                    </div>
                    <div class="sm:col-span-2">
                        <label class="mb-2 block text-sm font-medium text-gray-700">Rua</label>
                        <input type="text" name="endereco_rua" value="{{ old('endereco_rua') }}" class="w-full rounded border-[1.5px] border-stroke bg-gray-2 py-3 px-5 text-sm text-black focus:border-primary focus:outline-none" />
                    </div>
                    <div>
                        <label class="mb-2 block text-sm font-medium text-gray-700">Bairro</label>
                        <input type="text" name="bairro" value="{{ old('bairro') }}" class="w-full rounded border-[1.5px] border-stroke bg-gray-2 py-3 px-5 text-sm text-black focus:border-primary focus:outline-none" />
                    </div>
                    <div>
                        <label class="mb-2 block text-sm font-medium text-gray-700">Cidade</label>
                        <input type="text" name="cidade" value="{{ old('cidade') }}" class="w-full rounded border-[1.5px] border-stroke bg-gray-2 py-3 px-5 text-sm text-black focus:border-primary focus:outline-none" />
                    </div>
                    <div>
                        <label class="mb-2 block text-sm font-medium text-gray-700">Estado</label>
                        <input type="text" name="estado" value="{{ old('estado') }}" class="w-full rounded border-[1.5px] border-stroke bg-gray-2 py-3 px-5 text-sm text-black focus:border-primary focus:outline-none" />
                    </div>
                </div>
            </div>
        </div>


        <div class="flex justify-end gap-2 pt-4">
            <a href="{{ route('pacientes.index') }}" class="py-2 px-4 rounded border border-stroke text-gray-700">Cancelar</a>
            <button type="submit" class="py-2 px-4 bg-blue-600 text-white rounded hover:bg-blue-700">Salvar Paciente</button>
        </div>
    </form>
</div>
@endsection
