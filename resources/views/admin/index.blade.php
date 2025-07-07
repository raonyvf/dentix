@extends('layouts.app')

@section('content')
<div class="container mx-auto p-4">
    <div class="flex">
        <aside class="w-64" aria-label="Sidebar">
            <div class="px-3 py-4 overflow-y-auto rounded bg-gray-50 dark:bg-gray-800">
                <ul class="space-y-2 font-medium">
                    <li><a href="#" class="flex items-center p-2 text-gray-900 rounded-lg hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700">Dashboard</a></li>
                    <li><a href="#" class="flex items-center p-2 text-gray-900 rounded-lg hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700">Pacientes</a></li>
                    <li><a href="#" class="flex items-center p-2 text-gray-900 rounded-lg hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700">Agenda</a></li>
                    <li><a href="#" class="flex items-center p-2 text-gray-900 rounded-lg hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700">Prontuários</a></li>
                    <li><a href="#" class="flex items-center p-2 text-gray-900 rounded-lg hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700">Profissionais</a></li>
                    <li><a href="#" class="flex items-center p-2 text-gray-900 rounded-lg hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700">Estoque</a></li>
                    <li><a href="#" class="flex items-center p-2 text-gray-900 rounded-lg hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700">Financeiro</a></li>
                    <li><a href="#" class="flex items-center p-2 text-gray-900 rounded-lg hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700">Administração</a></li>
                    <li><a href="#" class="flex items-center p-2 text-gray-900 rounded-lg hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700">Relatórios</a></li>
                </ul>
            </div>
        </aside>
        <main class="flex-1 ml-4">
            <h1 class="text-2xl font-bold mb-4">Administração</h1>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                <div class="p-4 bg-white rounded shadow">
                    <h2 class="text-xl font-semibold mb-2">Clínicas</h2>
                    <p class="mb-2">Gerenciar informações da clínica</p>
                    <a href="{{ route('clinics.index') }}" class="text-blue-600">Acessar</a>
                </div>
                <div class="p-4 bg-white rounded shadow">
                    <h2 class="text-xl font-semibold mb-2">Unidades</h2>
                    <p class="mb-2">Gerenciar unidades</p>
                    <a href="{{ route('unidades.index') }}" class="text-blue-600">Acessar</a>
                </div>
                <div class="p-4 bg-white rounded shadow">
                    <h2 class="text-xl font-semibold mb-2">Cadeiras</h2>
                    <p class="mb-2">Gerenciar cadeiras</p>
                    <a href="{{ route('cadeiras.index') }}" class="text-blue-600">Acessar</a>
                </div>
            </div>
            <div class="flex space-x-4">
                <a href="{{ route('clinics.create') }}" class="px-4 py-2 bg-green-500 text-white rounded">+ Nova Clínica</a>
                <a href="{{ route('unidades.create') }}" class="px-4 py-2 bg-green-500 text-white rounded">+ Nova Unidade</a>
                <a href="{{ route('cadeiras.create') }}" class="px-4 py-2 bg-green-500 text-white rounded">+ Nova Cadeira</a>
            </div>
        </main>
    </div>
</div>
@endsection
