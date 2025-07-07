@extends('layouts.app')

@section('content')
<div class="max-w-xl mx-auto bg-white p-6 rounded-lg shadow">
    <h1 class="text-xl font-semibold mb-4">Criar Cadeira</h1>
    <form method="POST" action="{{ route('cadeiras.store') }}" class="space-y-4">
        @csrf
        <div>
            <label class="block text-sm font-medium text-gray-700">Unidade</label>
            <select name="unidade_id" required class="mt-1 w-full rounded-md border-gray-300">
                <option value="">Selecione</option>
                @foreach ($unidades as $unidade)
                    <option value="{{ $unidade->id }}">{{ $unidade->nome }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700">Nome</label>
            <input class="mt-1 w-full rounded-md border-gray-300" type="text" name="nome" required />
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700">Especialidade</label>
            <input class="mt-1 w-full rounded-md border-gray-300" type="text" name="especialidade" required />
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700">Status</label>
            <input class="mt-1 w-full rounded-md border-gray-300" type="text" name="status" required />
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700">Horários Disponíveis</label>
            <input class="mt-1 w-full rounded-md border-gray-300" type="text" name="horarios_disponiveis" required />
        </div>
        <button type="submit" class="py-2 px-4 bg-blue-600 text-white rounded hover:bg-blue-700">Salvar</button>
    </form>
</div>
@endsection
