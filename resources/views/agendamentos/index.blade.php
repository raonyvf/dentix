@extends('layouts.app')

@section('content')
@include('partials.breadcrumbs', ['crumbs' => [
    ['label' => 'Dashboard', 'url' => route('admin.index')],
    ['label' => 'Agenda', 'url' => route('agenda.index')],
    ['label' => 'Novo Agendamento']
]])
<div class="mb-6">
    <h1 class="text-2xl font-bold">Agendamentos</h1>
    <p class="text-gray-600">Agenda semanal por profissional</p>
</div>
@php
    // Dados de agenda são fornecidos pelo controlador
@endphp
<div x-data="agendaCalendar()" x-init="init()" data-horarios-url="{{ route('agendamentos.horarios') }}" data-professionals-url="{{ route('agendamentos.professionals') }}" data-base-times='@json($horarios)' data-current-date="{{ $date }}">
    <div class="flex justify-end mb-2 relative">
        <button @click="openDatePicker" class="p-2 border rounded bg-white">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
            </svg>
        </button>
        <input x-ref="picker" type="date" class="hidden" @change="onDateSelected($event.target.value)" />
        <span class="absolute -top-1 -right-1 bg-red-600 text-white text-[10px] rounded-full px-1">23</span>
    </div>
    <div class="flex items-center justify-between mb-4">
        <div class="flex items-center gap-2 flex-1">
            <button @click="prevWeek" class="p-1 border rounded bg-white">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
            </button>
            <div class="flex gap-2 flex-1">
                <template x-for="day in days" :key="day.date">
                    <div :class="day.classes" @click="selectDay(day.date)">
                        <span class="uppercase" x-text="day.label"></span>
                        <span class="font-semibold" x-text="day.number"></span>
                        <span x-text="day.month"></span>
                    </div>
                </template>
            </div>
            <button @click="nextWeek" class="p-1 border rounded bg-white">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
            </button>
        </div>
    </div>
<div id="professionals-bar" class="flex items-center gap-2 overflow-x-auto mb-4">
    <x-agenda.profissional name="Todos os Profissionais" active />
    @foreach($professionals as $prof)
        <x-agenda.profissional :name="$prof['name']" />
    @endforeach
</div>
<div class="flex space-x-6 border-b mb-4 text-sm">
    <button class="pb-2 border-b-2 border-primary text-primary">Por Consultório, Raony</button>
    <button class="pb-2 text-gray-600">Fila de Espera</button>
    <button class="pb-2 text-gray-600">Filtrar</button>
</div>
<div class="overflow-auto" id="schedule-container">
    <div id="schedule-closed" class="hidden text-center py-4 text-gray-500">A clínica está fechada neste dia.</div>
    <div id="schedule-empty" class="hidden text-center py-4 text-gray-500">Sem profissionais disponíveis neste dia.</div>
    <table id="schedule-table" class="min-w-full text-sm table-fixed">
        <thead>
            <tr>
                <th class="p-2 bg-gray-50 w-24 min-w-[6rem]"></th>
                @foreach($professionals as $prof)
                    <th class="p-2 bg-gray-50 text-left whitespace-nowrap">{{ $prof['name'] }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @foreach($horarios as $hora)
                <tr class="border-t" data-row="{{ $hora }}">
                    <td class="bg-gray-50 w-24 min-w-[6rem] h-16 align-middle" data-slot="{{ $hora }}" data-hora="{{ $hora }}"><x-agenda.horario :time="$hora" /></td>
                    @foreach($professionals as $prof)
                        <td class="h-16 cursor-pointer" data-professional="{{ $prof['id'] }}" data-time="{{ $hora }}" data-hora="{{ $hora }}">
                            @isset($agenda[$prof['id']][$hora])
                                @php $item = $agenda[$prof['id']][$hora]; @endphp
                                <x-agenda.agendamento :paciente="$item['paciente']" :tipo="$item['tipo']" :contato="$item['contato']" :status="$item['status']" />
                            @endisset
                        </td>
                    @endforeach
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
<div id="schedule-modal" data-time="" class="fixed inset-0 bg-black/50 hidden flex items-center justify-center z-50">
    <div class="bg-white rounded p-4 w-[32rem]">
        <h2 class="text-lg font-semibold mb-2">Agendar Horário</h2>
        <div class="flex gap-2 mb-4">
            <label class="block flex-1">
                <span class="text-sm">Início</span>
                <input id="schedule-start" type="time" class="mt-1 w-full border rounded p-1" />
            </label>
            <label class="block flex-1">
                <span class="text-sm">Fim</span>
                <input id="schedule-end" type="time" class="mt-1 w-full border rounded p-1" />
            </label>
        </div>
        <input type="hidden" id="hora_inicio" name="hora_inicio">
        <input type="hidden" id="hora_fim" name="hora_fim">
        <label class="block mb-4">
            <span class="text-sm">Paciente</span>
            <input id="schedule-paciente" type="text" list="schedule-paciente-list" placeholder="Buscar..." data-search-url="{{ route('pacientes.search') }}" class="mt-1 w-full border rounded p-1" />
            <datalist id="schedule-paciente-list"></datalist>
            <input type="hidden" id="schedule-paciente-id" />
        </label>
        <label class="block mb-4">
            <span class="text-sm">Observação</span>
            <textarea id="schedule-observacao" class="mt-1 w-full border rounded p-1" placeholder="Digite aqui"></textarea>
        </label>
        <div class="flex justify-end gap-2">
            <button id="schedule-cancel" class="px-3 py-1 border rounded">Cancelar</button>
            <button id="schedule-save" data-store-url="{{ route('agendamentos.store') }}" class="px-3 py-1 bg-primary text-white rounded">Salvar</button>
        </div>
        </div>
</div>

<div id="date-picker-modal" class="fixed inset-0 bg-black/50 hidden flex items-center justify-center z-50">
    <div class="bg-white rounded p-4 w-80">
        <div class="mb-2 flex items-center justify-between">
            <button type="button" id="dp-prev" class="px-2">&#60;</button>
            <span id="dp-month-label" class="font-semibold"></span>
            <button type="button" id="dp-next" class="px-2">&#62;</button>
        </div>
        <div id="dp-calendar" class="mb-2 text-sm"></div>
    </div>
</div>
</div>
@endsection
