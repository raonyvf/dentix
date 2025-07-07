@extends('layouts.app')

@section('content')
    <h1>Clínicas</h1>
    <a class="btn" href="{{ route('clinicas.create') }}">Nova Clínica</a>
    <table>
        <thead>
            <tr>
                <th>Nome</th>
                <th>CNPJ</th>
                <th>Responsável</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($clinics as $clinic)
                <tr>
                    <td>{{ $clinic->nome }}</td>
                    <td>{{ $clinic->cnpj }}</td>
                    <td>{{ $clinic->responsavel }}</td>
                </tr>
            @empty
                <tr><td colspan="3">Nenhuma clínica cadastrada.</td></tr>
            @endforelse
        </tbody>
    </table>
@endsection
