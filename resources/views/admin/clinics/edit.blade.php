@extends('layouts.app')

@section('content')
<div class="max-w-xl mx-auto bg-white p-6 rounded-lg shadow">
    <h1 class="text-xl font-semibold mb-4">Editar Clínica</h1>
    @if ($errors->any())
        <div class="mb-4">
            <ul class="list-disc list-inside text-sm text-red-600">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <form method="POST" action="{{ route('clinicas.update', $clinic) }}" class="space-y-4">
        @csrf
        @method('PUT')
        <div>
            <label class="mb-2 block text-sm font-medium text-gray-700">Nome</label>
            <input class="w-full rounded border-[1.5px] border-stroke bg-gray-2 py-3 px-5 text-sm text-black focus:border-primary focus:outline-none" type="text" name="nome" value="{{ old('nome', $clinic->nome) }}" required />
        </div>
        <div>
            <label class="mb-2 block text-sm font-medium text-gray-700">CNPJ</label>
            <input class="w-full rounded border-[1.5px] border-stroke bg-gray-2 py-3 px-5 text-sm text-black focus:border-primary focus:outline-none" type="text" name="cnpj" value="{{ old('cnpj', $clinic->cnpj) }}" required />
        </div>
        <div>
            <label class="mb-2 block text-sm font-medium text-gray-700">Responsável</label>
            <input class="w-full rounded border-[1.5px] border-stroke bg-gray-2 py-3 px-5 text-sm text-black focus:border-primary focus:outline-none" type="text" name="responsavel" value="{{ old('responsavel', $clinic->responsavel) }}" required />
        </div>
        <div>
            <label class="mb-2 block text-sm font-medium text-gray-700">Plano</label>
            <select name="plano_id" required class="w-full rounded border-[1.5px] border-stroke bg-gray-2 py-3 px-5 text-sm text-black focus:border-primary focus:outline-none">
                <option value="">Selecione</option>
                @foreach ($planos as $plano)
                    <option value="{{ $plano->id }}" @selected(old('plano_id', $clinic->plano_id) == $plano->id)>{{ $plano->nome }}</option>
                @endforeach
            </select>
        </div>
        <button type="submit" class="py-2 px-4 bg-blue-600 text-white rounded hover:bg-blue-700">Salvar</button>
    </form>
</div>
@endsection
