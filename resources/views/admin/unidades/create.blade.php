@extends('layouts.app')

@section('content')
    <h1>Criar Unidade</h1>
    <form method="POST" action="{{ route('unidades.store') }}">
        @csrf
        <div>
            <label>Clínica</label>
            <select name="clinic_id" required>
                <option value="">Selecione</option>
                @foreach ($clinics as $clinic)
                    <option value="{{ $clinic->id }}">{{ $clinic->nome }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label>Nome</label>
            <input type="text" name="nome" required />
        </div>
        <div>
            <label>Endereço</label>
            <input type="text" name="endereco" required />
        </div>
        <div>
            <label>Cidade</label>
            <input type="text" name="cidade" required />
        </div>
        <div>
            <label>Estado</label>
            <input type="text" name="estado" required />
        </div>
        <div>
            <label>Contato</label>
            <input type="text" name="contato" required />
        </div>
        <div>
            <label>Horários de Funcionamento</label>
            <input type="text" name="horarios_funcionamento" required />
        </div>
        <button type="submit" class="btn">Salvar</button>
    </form>
@endsection
