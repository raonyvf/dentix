@extends('layouts.app')

@section('content')
    <h1>Criar Clínica</h1>
    <form method="POST" action="{{ route('clinicas.store') }}">
        @csrf
        <div>
            <label>Nome</label>
            <input type="text" name="nome" required />
        </div>
        <div>
            <label>CNPJ</label>
            <input type="text" name="cnpj" required />
        </div>
        <div>
            <label>Responsável</label>
            <input type="text" name="responsavel" required />
        </div>
        <div>
            <label>Plano</label>
            <input type="text" name="plano" required />
        </div>
        <div>
            <label>Idioma Preferido</label>
            <input type="text" name="idioma_preferido" required />
        </div>
        <button type="submit" class="btn">Salvar</button>
    </form>
@endsection
