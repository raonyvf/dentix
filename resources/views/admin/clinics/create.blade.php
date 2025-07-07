@extends('layouts.app')

@section('content')
<div class="max-w-xl mx-auto bg-white p-6 rounded-lg shadow">
    <h1 class="text-xl font-semibold mb-4">Criar Clínica</h1>
    <form method="POST" action="{{ route('clinicas.store') }}" class="space-y-4">
        @csrf
        <div>
            <label class="block text-sm font-medium text-gray-700">Nome</label>
            <input class="mt-1 w-full rounded-md border-gray-300" type="text" name="nome" required />
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700">CNPJ</label>
            <input class="mt-1 w-full rounded-md border-gray-300" type="text" name="cnpj" required />
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700">Responsável</label>
            <input class="mt-1 w-full rounded-md border-gray-300" type="text" name="responsavel" required />
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700">Plano</label>
            <input class="mt-1 w-full rounded-md border-gray-300" type="text" name="plano" required />
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700">Idioma Preferido</label>
            <input class="mt-1 w-full rounded-md border-gray-300" type="text" name="idioma_preferido" required />
        </div>
        <button type="submit" class="py-2 px-4 bg-blue-600 text-white rounded hover:bg-blue-700">Salvar</button>
    </form>
</div>
@endsection
