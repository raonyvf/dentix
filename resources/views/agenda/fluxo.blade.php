@extends('layouts.app')

@section('content')
@include('partials.breadcrumbs', ['crumbs' => [
    ['label' => 'Dashboard', 'url' => route('admin.index')],
    ['label' => 'Agenda', 'url' => route('agenda.index')],
    ['label' => 'Fluxo'],
]])
<div class="mb-6 flex items-start justify-between">
    <div>
        <h1 class="text-2xl font-bold">Fluxo de Pacientes</h1>
        <p class="text-gray-600">Acompanhamento do fluxo de pacientes na clínica</p>
    </div>
</div>
<div class="border-b mb-6">
    <nav class="-mb-px flex gap-4">
        <a href="{{ route('agenda.index') }}" class="px-1 pb-2 text-gray-500 hover:text-gray-700">Agenda</a>
        <a href="{{ url('/admin/agenda/fluxo') }}" class="px-1 pb-2 border-b-2 border-blue-600 text-blue-600">Fluxo</a>
    </nav>
</div>
<p class="text-gray-600">Em construção.</p>
@endsection
