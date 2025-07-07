@extends('layouts.app')

@section('content')
    <h1>Cadeiras</h1>
    <a class="btn" href="{{ route('cadeiras.create') }}">Nova Cadeira</a>
    <table>
        <thead>
            <tr>
                <th>Nome</th>
                <th>Especialidade</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($cadeiras as $cadeira)
                <tr>
                    <td>{{ $cadeira->nome }}</td>
                    <td>{{ $cadeira->especialidade }}</td>
                    <td>{{ $cadeira->status }}</td>
                </tr>
            @empty
                <tr><td colspan="3">Nenhuma cadeira cadastrada.</td></tr>
            @endforelse
        </tbody>
    </table>
@endsection
