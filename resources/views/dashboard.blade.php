@extends('layouts.app')

@section('content')
@include('partials.breadcrumbs', ['crumbs' => [
    ['label' => 'Dashboard']
]])
<div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4">
    <div class="p-4 bg-white rounded-lg shadow">
        <p class="text-sm text-gray-500">Pacientes</p>
        <p class="mt-2 text-2xl font-semibold text-gray-700">350</p>
    </div>
    <div class="p-4 bg-white rounded-lg shadow">
        <p class="text-sm text-gray-500">Agendamentos</p>
        <p class="mt-2 text-2xl font-semibold text-gray-700">28</p>
    </div>
    <div class="p-4 bg-white rounded-lg shadow">
        <p class="text-sm text-gray-500">Faturamento</p>
        <p class="mt-2 text-2xl font-semibold text-gray-700">R$ 12k</p>
    </div>
    <div class="p-4 bg-white rounded-lg shadow">
        <p class="text-sm text-gray-500">Profissionais</p>
        <p class="mt-2 text-2xl font-semibold text-gray-700">8</p>
    </div>
</div>
@endsection
