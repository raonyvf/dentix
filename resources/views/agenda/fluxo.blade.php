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
        <a href="{{ route('agenda.index') }}" class="flex items-center gap-2 px-1 pb-2 border-b-2 border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="w-5 h-5">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
            </svg>
            Agenda
        </a>
        <a href="{{ route('agenda.fluxo') }}" class="flex items-center gap-2 px-1 pb-2 border-b-2 border-primary text-primary">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="w-5 h-5">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.5 10.5a7.5 7.5 0 0113.5-3m1.5-3v3m0-3h-3M19.5 13.5A7.5 7.5 0 016 16.5m-1.5 3v-3m0 3h3" />
            </svg>
            Fluxo
        </a>
    </nav>
</div>
<p class="text-gray-600">Em construção.</p>
@endsection
