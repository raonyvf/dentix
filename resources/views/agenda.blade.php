@extends('layouts.app')

@section('content')
@include('partials.breadcrumbs', ['crumbs' => [
    ['label' => 'Dashboard', 'url' => route('admin.index')],
    ['label' => 'Agenda']
]])
<div class="mb-6 flex items-start justify-between">
    <div>
        <h1 class="text-2xl font-bold">Agenda</h1>
        <p class="text-gray-600">Gerenciamento de consultas e horários</p>
    </div>
    <div class="flex items-center gap-2">
        <button class="p-2 text-gray-600 hover:text-gray-800">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6 6 0 10-12 0v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
            </svg>
        </button>
        <a href="{{ route('agendamentos.index') }}" class="py-2 px-4 bg-blue-600 text-white rounded hover:bg-blue-700 flex items-center">
            + Nova Consulta
        </a>
    </div>
</div>
<div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
    <div class="space-y-4">
        <div class="bg-white rounded-lg shadow p-4">
            <div class="flex items-center justify-between mb-2">
                <div class="flex items-center gap-2">
                    <a href="{{ route('agenda.index', ['year' => $prevDate->year, 'month' => $prevDate->month]) }}" class="p-1" title="Mês anterior">&lt;</a>
                    <h2 class="font-semibold">{{ $currentDate->translatedFormat('F Y') }}</h2>
                    <a href="{{ route('agenda.index', ['year' => $nextDate->year, 'month' => $nextDate->month]) }}" class="p-1" title="Próximo mês">&gt;</a>
                </div>
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
            </div>
            <div class="grid grid-cols-7 gap-1 text-center text-sm">
                <span class="font-semibold text-gray-600">D</span>
                <span class="font-semibold text-gray-600">S</span>
                <span class="font-semibold text-gray-600">T</span>
                <span class="font-semibold text-gray-600">Q</span>
                <span class="font-semibold text-gray-600">Q</span>
                <span class="font-semibold text-gray-600">S</span>
                <span class="font-semibold text-gray-600">S</span>
                @for($i = 1; $i < $firstDayOfWeek; $i++)
                    <span></span>
                @endfor
                @for($day = 1; $day <= $daysInMonth; $day++)
                    @php
                        $isToday = $today->year == $currentDate->year && $today->month == $currentDate->month && $today->day == $day;
                    @endphp
                    <span class="p-1 rounded {{ $isToday ? 'bg-blue-500 text-white' : '' }}">{{ $day }}</span>
                @endfor
            </div>
        </div>
        <div class="bg-white rounded-lg shadow p-4 space-y-4">
            <div>
                <label class="block text-sm font-medium mb-1" for="professional">Profissional</label>
                <select id="professional" class="w-full border-gray-300 rounded">
                    <option>Todos</option>
                    <option>Dr. João</option>
                    <option>Dra. Ana</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium mb-1" for="type">Tipo de Consulta</label>
                <select id="type" class="w-full border-gray-300 rounded">
                    <option>Todos</option>
                    <option>Consulta</option>
                    <option>Retorno</option>
                </select>
            </div>
        </div>
    </div>
    <div class="lg:col-span-1 space-y-4 lg:col-span-2">
        <div class="bg-white rounded-lg shadow p-4">
            <div class="flex justify-between items-start mb-4">
                <h2 class="text-lg font-bold">Consultas do Dia <span class="text-gray-500 font-normal">{{ $today->format('d/m/Y') }}</span></h2>
                <a href="#" class="py-2 px-3 bg-red-600 text-white text-sm rounded hover:bg-red-700">Bloquear Horário</a>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 text-sm">
                    <thead>
                        <tr class="bg-gray-50">
                            <th class="px-4 py-2 text-left font-medium text-gray-700">Horário</th>
                            <th class="px-4 py-2 text-left font-medium text-gray-700">Paciente</th>
                            <th class="px-4 py-2 text-left font-medium text-gray-700">Tipo</th>
                            <th class="px-4 py-2 text-left font-medium text-gray-700">Profissional</th>
                            <th class="px-4 py-2 text-left font-medium text-gray-700">Status</th>
                            <th class="px-4 py-2 text-left font-medium text-gray-700">Ações</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        <tr>
                            <td class="px-4 py-2 whitespace-nowrap">09:00</td>
                            <td class="px-4 py-2 whitespace-nowrap">Maria Silva</td>
                            <td class="px-4 py-2 whitespace-nowrap">Consulta</td>
                            <td class="px-4 py-2 whitespace-nowrap">Dr. João</td>
                            <td class="px-4 py-2 whitespace-nowrap">Confirmado</td>
                            <td class="px-4 py-2 whitespace-nowrap flex gap-2">
                                <button class="text-blue-600 hover:underline" title="Chat">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M21 12c0 1.657-1.343 3-3 3H7l-4 4V6c0-1.657 1.343-3 3-3h12c1.657 0 3 1.343 3 3v6z" />
                                    </svg>
                                </button>
                                <button class="text-blue-600 hover:underline" title="E-mail">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12H8m8-4H8m8 8H8m12 2H4a2 2 0 01-2-2V6a2 2 0 012-2h16a2 2 0 012 2v10a2 2 0 01-2 2z" />
                                    </svg>
                                </button>
                            </td>
                        </tr>
                        <tr>
                            <td class="px-4 py-2 whitespace-nowrap">10:30</td>
                            <td class="px-4 py-2 whitespace-nowrap">Pedro Santos</td>
                            <td class="px-4 py-2 whitespace-nowrap">Retorno</td>
                            <td class="px-4 py-2 whitespace-nowrap">Dra. Ana</td>
                            <td class="px-4 py-2 whitespace-nowrap">Aguardando</td>
                            <td class="px-4 py-2 whitespace-nowrap flex gap-2">
                                <button class="text-blue-600 hover:underline" title="Chat">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M21 12c0 1.657-1.343 3-3 3H7l-4 4V6c0-1.657 1.343-3 3-3h12c1.657 0 3 1.343 3 3v6z" />
                                    </svg>
                                </button>
                                <button class="text-blue-600 hover:underline" title="E-mail">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12H8m8-4H8m8 8H8m12 2H4a2 2 0 01-2-2V6a2 2 0 012-2h16a2 2 0 012 2v10a2 2 0 01-2 2z" />
                                    </svg>
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="bg-white rounded-lg shadow p-4">
            <h3 class="font-semibold mb-2">Próximas Notificações</h3>
            <div class="flex items-center justify-between p-2 border rounded">
                <span>Maria Silva - Amanhã às 09:00</span>
                <div class="flex gap-2">
                    <button class="text-blue-600 hover:underline" title="Chat">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M21 12c0 1.657-1.343 3-3 3H7l-4 4V6c0-1.657 1.343-3 3-3h12c1.657 0 3 1.343 3 3v6z" />
                        </svg>
                    </button>
                    <button class="text-blue-600 hover:underline" title="E-mail">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12H8m8-4H8m8 8H8m12 2H4a2 2 0 01-2-2V6a2 2 0 012-2h16a2 2 0 012 2v10a2 2 0 01-2 2z" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    </div>
    <div class="space-y-4">
        <div class="bg-white rounded-lg shadow p-4">
            <div class="flex justify-between items-center mb-2">
                <h2 class="font-semibold">Lista de Espera</h2>
                <button id="waitlist-add" class="p-1 text-blue-600 hover:text-blue-800">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                </button>
            </div>
            <div class="border rounded p-3 flex flex-col gap-2">
                <div class="font-medium">Juliana Prado</div>
                <div class="text-sm text-gray-500">(11) 99999-9999</div>
                <div class="flex justify-between items-center">
                    <span class="text-xs bg-green-100 text-green-800 px-2 py-0.5 rounded-full">Alta</span>
                    <button class="text-sm text-blue-600 hover:underline">Encaixar</button>
                </div>
            </div>
        </div>
    </div>
</div>
@include('agendamentos.partials.modal')
@endsection
