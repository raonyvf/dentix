@extends('layouts.app')

@section('content')
<div class="max-w-xl mx-auto bg-white p-6 rounded-lg shadow">
    <h1 class="text-xl font-semibold mb-4">Criar Unidade</h1>
    <form method="POST" action="{{ route('unidades.store') }}" class="space-y-4">
        @csrf
        <div>
            <label class="block text-sm font-medium text-gray-700">Clínica</label>
            <select name="clinic_id" required class="mt-1 w-full rounded-md border-gray-300">
                <option value="">Selecione</option>
                @foreach ($clinics as $clinic)
                    <option value="{{ $clinic->id }}">{{ $clinic->nome }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700">Nome</label>
            <input class="mt-1 w-full rounded-md border-gray-300" type="text" name="nome" required />
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700">Endereço</label>
            <input class="mt-1 w-full rounded-md border-gray-300" type="text" name="endereco" required />
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700">Cidade</label>
            <input class="mt-1 w-full rounded-md border-gray-300" type="text" name="cidade" required />
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700">Estado</label>
            <input class="mt-1 w-full rounded-md border-gray-300" type="text" name="estado" required />
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700">Contato</label>
            <input class="mt-1 w-full rounded-md border-gray-300" type="text" name="contato" required />
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700">Horários de Funcionamento</label>
            <input class="mt-1 w-full rounded-md border-gray-300" type="text" name="horarios_funcionamento" required />
        </div>
        <button type="submit" class="py-2 px-4 bg-blue-600 text-white rounded hover:bg-blue-700">Salvar</button>
    </form>
</div>
@endsection
