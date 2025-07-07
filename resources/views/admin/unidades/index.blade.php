@extends('layouts.app')

@section('content')
    <h1>Unidades</h1>
    <a class="btn" href="{{ route('unidades.create') }}">Nova Unidade</a>
    <table>
        <thead>
            <tr>
                <th>Nome</th>
                <th>Cidade</th>
                <th>Estado</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($unidades as $unidade)
                <tr>
                    <td>{{ $unidade->nome }}</td>
                    <td>{{ $unidade->cidade }}</td>
                    <td>{{ $unidade->estado }}</td>
                </tr>
            @empty
                <tr><td colspan="3">Nenhuma unidade cadastrada.</td></tr>
            @endforelse
        </tbody>
    </table>
@endsection
