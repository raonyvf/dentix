@extends('layouts.app')

@section('content')
<div class="max-w-xl mx-auto bg-white p-6 rounded-lg shadow">
    <h1 class="text-xl font-semibold mb-4">Criar Unidade</h1>
    <form method="POST" action="{{ route('unidades.store') }}" class="space-y-4">
        @csrf
        <div>
            <label class="mb-2 block text-sm font-medium text-gray-700">Clínica</label>
            <select name="clinic_id" required class="w-full rounded border-[1.5px] border-stroke bg-gray-2 py-3 px-5 text-sm text-black focus:border-primary focus:outline-none">
                <option value="">Selecione</option>
                @foreach ($clinics as $clinic)
                    <option value="{{ $clinic->id }}">{{ $clinic->nome }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="mb-2 block text-sm font-medium text-gray-700">Nome</label>
            <input class="w-full rounded border-[1.5px] border-stroke bg-gray-2 py-3 px-5 text-sm text-black focus:border-primary focus:outline-none" type="text" name="nome" required />
        </div>
        <div>
            <label class="mb-2 block text-sm font-medium text-gray-700">Endereço</label>
            <input class="w-full rounded border-[1.5px] border-stroke bg-gray-2 py-3 px-5 text-sm text-black focus:border-primary focus:outline-none" type="text" name="endereco" required />
        </div>
        <div>
            <label class="mb-2 block text-sm font-medium text-gray-700">Cidade</label>
            <input class="w-full rounded border-[1.5px] border-stroke bg-gray-2 py-3 px-5 text-sm text-black focus:border-primary focus:outline-none" type="text" name="cidade" required />
        </div>
        <div>
            <label class="mb-2 block text-sm font-medium text-gray-700">Estado</label>
            <input class="w-full rounded border-[1.5px] border-stroke bg-gray-2 py-3 px-5 text-sm text-black focus:border-primary focus:outline-none" type="text" name="estado" required />
        </div>
        <div>
            <label class="mb-2 block text-sm font-medium text-gray-700">Contato</label>
            <input class="w-full rounded border-[1.5px] border-stroke bg-gray-2 py-3 px-5 text-sm text-black focus:border-primary focus:outline-none" type="text" name="contato" required />
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
