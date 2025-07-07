@extends('layouts.app')

@section('content')
    <h1>Criar Cadeira</h1>
    <form method="POST" action="{{ route('cadeiras.store') }}">
        @csrf
        <div>
            <label>Unidade</label>
            <select name="unidade_id" required>
                <option value="">Selecione</option>
                @foreach ($unidades as $unidade)
                    <option value="{{ $unidade->id }}">{{ $unidade->nome }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label>Nome</label>
            <input type="text" name="nome" required />
        </div>
        <div>
            <label>Especialidade</label>
            <input type="text" name="especialidade" required />
        </div>
        <div>
            <label>Status</label>
            <input type="text" name="status" required />
        </div>
        <div>
            <label>Horários Disponíveis</label>
            <input type="text" name="horarios_disponiveis" required />
        </div>
        <button type="submit" class="btn">Salvar</button>
    </form>
@endsection
