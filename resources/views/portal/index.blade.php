@extends('layouts.app')

@section('content')
@include('partials.breadcrumbs', ['crumbs' => [ ['label' => 'Portal'] ]])
<div class="bg-white p-6 rounded-lg shadow">
    @include('pacientes.financeiro')
    <div class="mt-6 text-right">
        <a href="{{ route('portal.agendamentos') }}" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Agendar Consulta</a>
    </div>
</div>
@endsection
