@extends('layouts.app')

@section('content')
@include('partials.breadcrumbs', ['crumbs' => [
    ['label' => 'Dashboard', 'url' => route('admin.index')],
    ['label' => 'Clínicas', 'url' => route('clinicas.index')],
    ['label' => 'Criar']
]])
<div class="w-full bg-white p-6 rounded-lg shadow">
    <h1 class="text-xl font-semibold mb-4">Criar Clínica</h1>
    @if ($errors->any())
        <div class="mb-4">
            <ul class="list-disc list-inside text-sm text-red-600">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <form method="POST" action="{{ route('clinicas.store') }}" class="space-y-6">
        @csrf
        <div class="rounded-sm border border-stroke bg-gray-50 p-4">
            <h2 class="mb-4 text-sm font-medium text-gray-700">Dados da Clínica</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div class="sm:col-span-2">
                    <label class="mb-2 block text-sm font-medium text-gray-700">Nome</label>
                    <input class="w-full rounded border-[1.5px] border-stroke bg-gray-2 py-3 px-5 text-sm text-black focus:border-primary focus:outline-none" type="text" name="nome" value="{{ old('nome') }}" required />
                </div>
                <div>
                    <label class="mb-2 block text-sm font-medium text-gray-700">CNPJ</label>
                    <input class="w-full rounded border-[1.5px] border-stroke bg-gray-2 py-3 px-5 text-sm text-black focus:border-primary focus:outline-none" type="text" name="cnpj" value="{{ old('cnpj') }}" required />
                </div>
                <div>
                    <label class="mb-2 block text-sm font-medium text-gray-700">Responsável Técnico</label>
                    <input class="w-full rounded border-[1.5px] border-stroke bg-gray-2 py-3 px-5 text-sm text-black focus:border-primary focus:outline-none" type="text" name="responsavel_tecnico" value="{{ old('responsavel_tecnico') }}" required />
                </div>
                <div>
                    <label class="mb-2 block text-sm font-medium text-gray-700">CRO</label>
                    <input class="w-full rounded border-[1.5px] border-stroke bg-gray-2 py-3 px-5 text-sm text-black focus:border-primary focus:outline-none" type="text" name="cro" value="{{ old('cro') }}" required />
                </div>
            </div>
        </div>
        <div class="rounded-sm border border-stroke bg-gray-50 p-4">
            <h2 class="mb-4 text-sm font-medium text-gray-700">Endereço</h2>
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                <div class="sm:col-span-2">
                    <label class="mb-2 block text-sm font-medium text-gray-700">Logradouro</label>
                    <input class="w-full rounded border-[1.5px] border-stroke bg-gray-2 py-3 px-5 text-sm text-black focus:border-primary focus:outline-none" type="text" name="endereco" value="{{ old('endereco') }}" placeholder="Número e complemento" required />
                </div>
                <div>
                    <label class="mb-2 block text-sm font-medium text-gray-700">Cidade</label>
                    <input class="w-full rounded border-[1.5px] border-stroke bg-gray-2 py-3 px-5 text-sm text-black focus:border-primary focus:outline-none" type="text" name="cidade" value="{{ old('cidade') }}" required />
                </div>
                <div>
                    <label class="mb-2 block text-sm font-medium text-gray-700">Estado</label>
                    <input class="w-full rounded border-[1.5px] border-stroke bg-gray-2 py-3 px-5 text-sm text-black focus:border-primary focus:outline-none" type="text" name="estado" value="{{ old('estado') }}" required />
                </div>
                <div>
                    <label class="mb-2 block text-sm font-medium text-gray-700">CEP</label>
                    <input class="w-full rounded border-[1.5px] border-stroke bg-gray-2 py-3 px-5 text-sm text-black focus:border-primary focus:outline-none" type="text" name="cep" value="{{ old('cep') }}" required />
                </div>
            </div>
        </div>
        <div class="rounded-sm border border-stroke bg-gray-50 p-4">
            <h2 class="mb-4 text-sm font-medium text-gray-700">Contato</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="mb-2 block text-sm font-medium text-gray-700">Telefone</label>
                    <input class="w-full rounded border-[1.5px] border-stroke bg-gray-2 py-3 px-5 text-sm text-black focus:border-primary focus:outline-none" type="text" name="telefone" value="{{ old('telefone') }}" required />
                </div>
                <div>
                    <label class="mb-2 block text-sm font-medium text-gray-700">E-mail</label>
                    <input class="w-full rounded border-[1.5px] border-stroke bg-gray-2 py-3 px-5 text-sm text-black focus:border-primary focus:outline-none" type="email" name="email" value="{{ old('email') }}" required />
                </div>
            </div>
        </div>
        <div>
            <label class="mb-2 block text-sm font-medium text-gray-700">Horários de Funcionamento</label>
            @php
                $diasSemana = [
                    'segunda' => 'Segunda-feira',
                    'terca' => 'Terça-feira',
                    'quarta' => 'Quarta-feira',
                    'quinta' => 'Quinta-feira',
                    'sexta' => 'Sexta-feira',
                    'sabado' => 'Sábado',
                    'domingo' => 'Domingo',
                ];
            @endphp
            @foreach ($diasSemana as $diaKey => $diaLabel)
                <div class="flex items-center space-x-2 mb-2">
                    <span class="w-40 text-sm whitespace-nowrap flex-shrink-0">{{ $diaLabel }}</span>
                    <input type="time" name="horarios[{{ $diaKey }}][abertura]" value="{{ old('horarios.' . $diaKey . '.abertura') }}" class="w-full rounded border-[1.5px] border-stroke bg-gray-2 py-2 px-3 text-sm text-black focus:border-primary focus:outline-none" />
                    <input type="time" name="horarios[{{ $diaKey }}][fechamento]" value="{{ old('horarios.' . $diaKey . '.fechamento') }}" class="w-full rounded border-[1.5px] border-stroke bg-gray-2 py-2 px-3 text-sm text-black focus:border-primary focus:outline-none" />
                </div>
            @endforeach
        </div>
        <button type="submit" class="py-2 px-4 bg-blue-600 text-white rounded hover:bg-blue-700">Salvar</button>
    </form>
</div>
@endsection
