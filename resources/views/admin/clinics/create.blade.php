@extends('layouts.app')

@section('content')
<div class="max-w-xl mx-auto bg-white p-6 rounded-lg shadow">
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
    <form method="POST" action="{{ route('clinicas.store') }}" class="space-y-4">
        @csrf
        <div>
            <label class="mb-2 block text-sm font-medium text-gray-700">Nome</label>
            <input class="w-full rounded border-[1.5px] border-stroke bg-gray-2 py-3 px-5 text-sm text-black focus:border-primary focus:outline-none" type="text" name="nome" value="{{ old('nome') }}" required />
        </div>
        <div>
            <label class="mb-2 block text-sm font-medium text-gray-700">CNPJ</label>
            <input class="w-full rounded border-[1.5px] border-stroke bg-gray-2 py-3 px-5 text-sm text-black focus:border-primary focus:outline-none" type="text" name="cnpj" value="{{ old('cnpj') }}" required />
        </div>
        <div>
            <label class="mb-2 block text-sm font-medium text-gray-700">Responsável</label>
            <input class="w-full rounded border-[1.5px] border-stroke bg-gray-2 py-3 px-5 text-sm text-black focus:border-primary focus:outline-none" type="text" name="responsavel" value="{{ old('responsavel') }}" required />
        </div>
        <div class="rounded-sm border border-stroke bg-gray-50 p-4">
            <h2 class="mb-4 text-sm font-medium text-gray-700">Endereço</h2>
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                <div class="sm:col-span-2">
                    <label class="mb-2 block text-sm font-medium text-gray-700">Logradouro</label>
                    <input class="w-full rounded border-[1.5px] border-stroke bg-gray-2 py-3 px-5 text-sm text-black focus:border-primary focus:outline-none" type="text" name="endereco" value="{{ old('endereco') }}" required />
                </div>
                <div>
                    <label class="mb-2 block text-sm font-medium text-gray-700">Cidade</label>
                    <input class="w-full rounded border-[1.5px] border-stroke bg-gray-2 py-3 px-5 text-sm text-black focus:border-primary focus:outline-none" type="text" name="cidade" value="{{ old('cidade') }}" required />
                </div>
                <div>
                    <label class="mb-2 block text-sm font-medium text-gray-700">Estado</label>
                    <input class="w-full rounded border-[1.5px] border-stroke bg-gray-2 py-3 px-5 text-sm text-black focus:border-primary focus:outline-none" type="text" name="estado" value="{{ old('estado') }}" required />
                </div>
            </div>
        </div>
        <div>
            <label class="mb-2 block text-sm font-medium text-gray-700">Contato</label>
            <input class="w-full rounded border-[1.5px] border-stroke bg-gray-2 py-3 px-5 text-sm text-black focus:border-primary focus:outline-none" type="text" name="contato" value="{{ old('contato') }}" required />
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
                    <span class="w-32 text-sm">{{ $diaLabel }}</span>
                    <input type="time" name="horarios[{{ $diaKey }}][abertura]" value="{{ old('horarios.' . $diaKey . '.abertura') }}" class="w-full rounded border-[1.5px] border-stroke bg-gray-2 py-2 px-3 text-sm text-black focus:border-primary focus:outline-none" />
                    <input type="time" name="horarios[{{ $diaKey }}][fechamento]" value="{{ old('horarios.' . $diaKey . '.fechamento') }}" class="w-full rounded border-[1.5px] border-stroke bg-gray-2 py-2 px-3 text-sm text-black focus:border-primary focus:outline-none" />
                </div>
            @endforeach
        </div>
        <button type="submit" class="py-2 px-4 bg-blue-600 text-white rounded hover:bg-blue-700">Salvar</button>
    </form>
</div>
@endsection
