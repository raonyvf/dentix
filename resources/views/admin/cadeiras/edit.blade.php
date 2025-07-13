@extends('layouts.app')

@section('content')
<div class="max-w-xl mx-auto bg-white p-6 rounded-lg shadow">
    <h1 class="text-xl font-semibold mb-4">Editar Cadeira</h1>
    @if ($errors->any())
        <div class="mb-4">
            <ul class="list-disc list-inside text-sm text-red-600">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <form method="POST" action="{{ route('cadeiras.update', $cadeira) }}" class="space-y-4">
        @csrf
        @method('PUT')
        <div>
            <label class="mb-2 block text-sm font-medium text-gray-700">Unidade</label>
            <select name="unidade_id" required class="w-full rounded border-[1.5px] border-stroke bg-gray-2 py-3 px-5 text-sm text-black focus:border-primary focus:outline-none">
                <option value="">Selecione</option>
                @foreach ($unidades as $unidade)
                    <option value="{{ $unidade->id }}" @selected(old('unidade_id', $cadeira->unidade_id) == $unidade->id)>{{ $unidade->nome }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="mb-2 block text-sm font-medium text-gray-700">Nome</label>
            <input class="w-full rounded border-[1.5px] border-stroke bg-gray-2 py-3 px-5 text-sm text-black focus:border-primary focus:outline-none" type="text" name="nome" value="{{ old('nome', $cadeira->nome) }}" required />
        </div>
        <div>
            <label class="mb-2 block text-sm font-medium text-gray-700">Especialidade</label>
            <input class="w-full rounded border-[1.5px] border-stroke bg-gray-2 py-3 px-5 text-sm text-black focus:border-primary focus:outline-none" type="text" name="especialidade" value="{{ old('especialidade', $cadeira->especialidade) }}" required />
        </div>
        <div>
            <label class="mb-2 block text-sm font-medium text-gray-700">Status</label>
            <select name="status" required class="w-full rounded border-[1.5px] border-stroke bg-gray-2 py-3 px-5 text-sm text-black focus:border-primary focus:outline-none">
                <option value="Ativa" @selected(old('status', $cadeira->status) === 'Ativa')>Ativa</option>
                <option value="Desativa" @selected(old('status', $cadeira->status) === 'Desativa')>Desativa</option>
            </select>
        </div>
        <button type="submit" class="py-2 px-4 bg-blue-600 text-white rounded hover:bg-blue-700">Salvar</button>
    </form>
</div>
@endsection
