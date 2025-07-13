@extends('layouts.app')

@section('content')
<div class="max-w-xl mx-auto bg-white p-6 rounded-lg shadow">
    <h1 class="text-xl font-semibold mb-4">Criar Cadeira</h1>
    <form method="POST" action="{{ route('cadeiras.store') }}" class="space-y-4">
        @csrf
        <div>
            <label class="mb-2 block text-sm font-medium text-gray-700">Unidade</label>
            <select name="unidade_id" required class="w-full rounded border-[1.5px] border-stroke bg-gray-2 py-3 px-5 text-sm text-black focus:border-primary focus:outline-none">
                <option value="">Selecione</option>
                @foreach ($unidades as $unidade)
                    <option value="{{ $unidade->id }}">{{ $unidade->nome }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="mb-2 block text-sm font-medium text-gray-700">Nome</label>
            <input class="w-full rounded border-[1.5px] border-stroke bg-gray-2 py-3 px-5 text-sm text-black focus:border-primary focus:outline-none" type="text" name="nome" required />
        </div>
        <div>
            <label class="mb-2 block text-sm font-medium text-gray-700">Especialidade</label>
            <input class="w-full rounded border-[1.5px] border-stroke bg-gray-2 py-3 px-5 text-sm text-black focus:border-primary focus:outline-none" type="text" name="especialidade" required />
        </div>
        <div>
            <label class="mb-2 block text-sm font-medium text-gray-700">Status</label>
            <select name="status" required class="w-full rounded border-[1.5px] border-stroke bg-gray-2 py-3 px-5 text-sm text-black focus:border-primary focus:outline-none">
                <option value="Ativa" @selected(old('status') === 'Ativa')>Ativa</option>
                <option value="Desativa" @selected(old('status') === 'Desativa')>Desativa</option>
            </select>
        </div>

        <button type="submit" class="py-2 px-4 bg-blue-600 text-white rounded hover:bg-blue-700">Salvar</button>
    </form>
</div>
@endsection
