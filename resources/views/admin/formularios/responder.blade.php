@extends('layouts.app')

@section('content')
@include('partials.breadcrumbs', ['crumbs' => [
    ['label' => 'Dashboard', 'url' => route('admin.index')],
    ['label' => 'Pacientes', 'url' => route('pacientes.index')],
    ['label' => $paciente->nome, 'url' => route('pacientes.edit', $paciente)],
    ['label' => $formulario->nome]
]])
<div class="w-full bg-white p-6 rounded-lg shadow">
    <h1 class="text-xl font-semibold mb-4">{{ $formulario->nome }}</h1>
    @if ($errors->any())
        <x-alert-error>{{ implode(', ', $errors->all()) }}</x-alert-error>
    @endif
    <form method="POST" action="{{ route('pacientes.formularios.store', [$paciente,$formulario]) }}" class="space-y-4">
        @csrf
        @foreach ($formulario->perguntas as $pergunta)
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">{{ $pergunta->enunciado }}</label>
                @if ($pergunta->tipo === 'texto')
                    <input type="text" name="respostas[{{ $pergunta->id }}]" class="w-full rounded border border-stroke py-2 px-3" />
                @elseif ($pergunta->tipo === 'select')
                    <select name="respostas[{{ $pergunta->id }}]" class="w-full rounded border border-stroke py-2 px-3">
                        @foreach ($pergunta->opcoesArray() as $opcao)
                            <option value="{{ $opcao }}">{{ $opcao }}</option>
                        @endforeach
                    </select>
                @elseif ($pergunta->tipo === 'checkbox')
                    @foreach ($pergunta->opcoesArray() as $opcao)
                        <label class="mr-4">
                            <input type="checkbox" name="respostas[{{ $pergunta->id }}][]" value="{{ $opcao }}"> {{ $opcao }}
                        </label>
                    @endforeach
                @elseif ($pergunta->tipo === 'radio')
                    @foreach ($pergunta->opcoesArray() as $opcao)
                        <label class="mr-4">
                            <input type="radio" name="respostas[{{ $pergunta->id }}]" value="{{ $opcao }}"> {{ $opcao }}
                        </label>
                    @endforeach
                @endif
            </div>
        @endforeach
        <button type="submit" class="py-2 px-4 bg-blue-600 text-white rounded">Enviar</button>
    </form>
</div>
@endsection
