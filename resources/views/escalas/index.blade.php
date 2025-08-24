@extends('layouts.app')

@section('content')
@include('partials.breadcrumbs', ['crumbs' => [
    ['label' => 'Dashboard', 'url' => route('admin.index')],
    ['label' => 'Escalas de Trabalho']
]])
<div class="mb-6 flex flex-wrap justify-between items-center gap-4">
    <h1 class="text-2xl font-bold">Escalas de Trabalho</h1>
    <div class="flex gap-2">
        <button id="open-modal" class="flex items-center gap-1 px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            <span>Adicionar Escala</span>
        </button>
    </div>
</div>
<form method="GET" class="flex flex-wrap gap-4 mb-4">
    <div>
        <label class="block text-sm font-medium mb-1">Clínica</label>
        <div class="relative">
            <select name="clinic_id" class="w-full rounded border border-stroke bg-transparent py-2 pl-3 pr-10 focus:border-primary" onchange="this.form.submit()">
                @foreach($clinics as $c)
                    <option value="{{ $c->id }}" @selected($clinicId==$c->id)>{{ $c->nome }}</option>
                @endforeach
            </select>
            {{-- Removed custom arrow to use native browser arrow --}}
        </div>
    </div>
    <div>
        <label class="block text-sm font-medium mb-1">Ano</label>
        <input type="number" name="year" id="anoSelecionado" class="border rounded px-2 py-1" value="{{ $month->year }}" onchange="this.form.submit()">
    </div>
    <div>
        <label class="block text-sm font-medium mb-1">Mês</label>
        <div class="relative">
            <select name="month" id="mesSelecionado" class="w-full rounded border border-stroke bg-transparent py-2 pl-3 pr-10 focus:border-primary" onchange="this.form.submit()">
                @foreach($mesesDisponiveis as $mes)
                    <option value="{{ $mes->month }}" @selected($mes->equalTo($month))>{{ $mes->translatedFormat('F') }}</option>
                @endforeach
            </select>
            {{-- Removed custom arrow to use native browser arrow --}}
        </div>
    </div>
    <div class="flex items-end gap-2">
        <button type="button" id="prev-month-btn" class="px-2 py-1 border rounded">Anterior</button>
        <button type="button" id="next-month-btn" class="px-2 py-1 border rounded">Próximo</button>
    </div>
</form>
@php $clinic = $clinics->firstWhere('id', $clinicId); @endphp
@foreach($weeks as $w)
    <h2 class="mt-6 font-semibold">Semana de {{ $w->format('d/m/Y') }}</h2>
    <div class="overflow-x-auto bg-white rounded shadow mb-6">
        <table class="table-fixed w-full text-sm border">
            <thead>
                <tr>
                    <th class="w-32 px-2 py-1 bg-white font-semibold border border-gray-200">Cadeira</th>
                    @php $weekStart = $w; @endphp
                    @foreach($dias as $d)
                        @php
                            $date = $weekStart->copy()->addDays($loop->index);
                            $outMonth = !$date->isSameMonth($month);
                        @endphp
                        <th class="px-2 py-1 text-center font-semibold capitalize border border-gray-200 {{ $outMonth ? 'bg-gray-100 text-gray-500' : 'bg-white' }}" style="width:14.28%">
                            {{ ucfirst($d->toName()) }}<br>
                            <span class="text-xs {{ $outMonth ? 'text-gray-400' : 'text-gray-500' }}">{{ $date->format('d/m') }}</span>
                        </th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @foreach($cadeiras as $cadeira)
                    <tr>
                        <td class="w-32 px-2 py-1 font-semibold bg-gray-50 border border-gray-200">{{ $cadeira->nome }}</td>
                        @foreach($dias as $d)
                            @php
                                $date = $w->copy()->addDays($loop->index);
                                $outMonth = !$date->isSameMonth($month);
                                $items = collect(data_get($escalas, $w->toDateString().'.'.$cadeira->id.'.'.$d->value, []))->sortBy('hora_inicio');
                            @endphp
                            <td class="px-2 py-2 border border-gray-200 align-top space-y-2 {{ $outMonth ? 'bg-gray-100 text-gray-500' : '' }}" style="width:14.28%">
                                @forelse($items as $it)
                                    @include('escalas.partials.card', ['it' => $it, 'clinic' => $clinic, 'diaSemana' => $d->value, 'out' => $outMonth])
                                @empty
                                    <span class="text-sm {{ $outMonth ? 'text-gray-500' : 'text-gray-400' }}">Livre</span>
                                @endforelse
                            </td>
                        @endforeach
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endforeach
<div id="escala-modal" class="fixed inset-0 bg-black/50 hidden flex items-center justify-center z-50">
    <div class="bg-white rounded p-4 w-96">
        <form method="POST" action="{{ route('escalas.store') }}" class="space-y-4">
            @csrf
            <input type="hidden" name="clinic_id" value="{{ $clinicId }}">
            <input type="hidden" name="year" id="form-year" value="{{ $month->year }}">
            <input type="hidden" name="month" id="form-month" value="{{ $month->month }}">
            <input type="hidden" id="calendar-month-input" value="{{ $month->format('Y-m') }}">
            <div>
                <label class="block text-sm mb-1">Profissional</label>
                <div class="relative">
                    <select name="profissional_id" class="w-full rounded border border-stroke bg-transparent py-2 pl-3 pr-10 focus:border-primary">
                        @foreach($dentistas as $d)
                            <option value="{{ $d->id }}">{{ $d->pessoa->primeiro_nome }} {{ $d->pessoa->ultimo_nome }}</option>
                        @endforeach
                    </select>
                    {{-- Removed custom arrow to use native browser arrow --}}
                </div>
            </div>
            <div class="mb-2">
                <nav class="flex border-b">
                    <button type="button" data-tab="daily" class="tab-btn px-3 py-1 border-b-2 border-blue-600">Diário</button>
                    <button type="button" data-tab="recurring" class="tab-btn px-3 py-1 border-b-2 border-transparent">Recorrente</button>
                </nav>
            </div>
            <div id="tab-daily">
                <div class="mb-2 flex items-center justify-between">
                    <button type="button" id="prev-month" class="px-2">&#60;</button>
                    <span id="calendar-month-label" class="font-semibold"></span>
                    <button type="button" id="next-month" class="px-2">&#62;</button>
                </div>
                <div id="calendar-table" class="mb-2 text-sm"></div>
                <div id="selected-dates"></div>
            </div>
            <div id="tab-recurring" class="hidden">
                <div class="flex gap-2 mb-2">
                    <div>
                        <label class="block text-sm mb-1">Semana inicial</label>
                        <input type="date" name="semana" class="border rounded px-2 py-1" disabled>
                    </div>
                    <div class="flex-1">
                        <label class="block text-sm mb-1">Dia da semana</label>
                        <div class="relative">
                            <select name="dias[]" multiple class="w-full rounded border border-stroke bg-transparent py-2 pl-3 pr-10 focus:border-primary" disabled>
                                @foreach($dias as $d)
                                    <option value="{{ $d->toName() }}">{{ ucfirst($d->toName()) }}</option>
                                @endforeach
                            </select>
                            {{-- Removed custom arrow to use native browser arrow --}}
                        </div>
                    </div>
                </div>
                <div class="flex gap-2 mb-2">
                    <div>
                        <label class="block text-sm mb-1">Repetir até</label>
                        <input type="date" name="repeat_until" class="border rounded px-2 py-1" disabled>
                    </div>
                    <div>
                        <label class="block text-sm mb-1">Semanas</label>
                        <input type="number" name="repeat_weeks" min="1" class="border rounded px-2 py-1" disabled>
                    </div>
                </div>
                <div class="mb-2">
                    <label class="inline-flex items-center">
                        <input type="checkbox" id="apply-year" class="mr-1" disabled>
                        Aplicar para o ano inteiro
                    </label>
                </div>
            </div>
            <div class="flex gap-2 items-end">
                <div>
                    <label class="block text-sm mb-1">Início</label>
                    <input type="time" name="hora_inicio" class="border rounded px-2 py-1">
                </div>
                <div>
                    <label class="block text-sm mb-1">Fim</label>
                    <input type="time" name="hora_fim" class="border rounded px-2 py-1">
                </div>
            </div>
            <div>
                <label class="block text-sm mb-1">Cadeira</label>
                <div class="relative">
                    <select name="cadeira_id" class="w-full rounded border border-stroke bg-transparent py-2 pl-3 pr-10 focus:border-primary">
                        @foreach($cadeiras as $c)
                            <option value="{{ $c->id }}">{{ $c->nome }}</option>
                        @endforeach
                    </select>
                    {{-- Removed custom arrow to use native browser arrow --}}
                </div>
            </div>
            <div class="text-right space-x-2">
                <button type="button" id="escala-delete" class="px-3 py-1 border rounded text-red-600 hidden">Excluir</button>
                <button type="button" id="escala-cancel" class="px-3 py-1 border rounded">Cancelar</button>
                <button class="px-3 py-1 bg-blue-600 text-white rounded">Salvar</button>
            </div>
        </form>
    </div>
</div>
@push('scripts')
<script>
    const escalaModal = document.getElementById('escala-modal');
    const escalaForm = escalaModal.querySelector('form');
    const deleteBtn = document.getElementById('escala-delete');
    const monthSelectTop = document.getElementById('mesSelecionado');
    const yearInputTop = document.getElementById('anoSelecionado');
    const prevNav = document.getElementById('prev-month-btn');
    const nextNav = document.getElementById('next-month-btn');
    const applyYear = document.getElementById('apply-year');
    const tabButtons = escalaModal.querySelectorAll('.tab-btn');
    const tabDaily = document.getElementById('tab-daily');
    const tabRecurring = document.getElementById('tab-recurring');
    const recurringInputs = tabRecurring.querySelectorAll('input, select');

    function activateTab(name) {
        if (name === 'daily') {
            tabDaily.classList.remove('hidden');
            tabRecurring.classList.add('hidden');
            tabButtons.forEach(b => b.classList.remove('border-blue-600'));
            escalaModal.querySelector('[data-tab="daily"]').classList.add('border-blue-600');
            recurringInputs.forEach(el => {
                el.disabled = true;
                if (el.type === 'checkbox') {
                    el.checked = false;
                } else {
                    el.value = '';
                }
            });
            document.getElementById('selected-dates').innerHTML = '';
        } else {
            tabDaily.classList.add('hidden');
            tabRecurring.classList.remove('hidden');
            tabButtons.forEach(b => b.classList.remove('border-blue-600'));
            escalaModal.querySelector('[data-tab="recurring"]').classList.add('border-blue-600');
            recurringInputs.forEach(el => {
                el.disabled = false;
            });
            document.getElementById('selected-dates').innerHTML = '';
        }
    }

    tabButtons.forEach(btn => {
        btn.addEventListener('click', () => activateTab(btn.dataset.tab));
    });

    if (prevNav && nextNav && monthSelectTop && yearInputTop) {
        function changeMonth(delta) {
            let year = parseInt(yearInputTop.value);
            let month = parseInt(monthSelectTop.value);
            month += delta;
            if (month < 1) { month = 12; year--; }
            if (month > 12) { month = 1; year++; }
            monthSelectTop.value = month;
            yearInputTop.value = year;
            monthSelectTop.form.submit();
        }
        prevNav.addEventListener('click', () => changeMonth(-1));
        nextNav.addEventListener('click', () => changeMonth(1));
    }
    document.getElementById('open-modal').addEventListener('click', () => {
        escalaForm.reset();
        escalaForm.action = '{{ route('escalas.store') }}';
        const method = escalaForm.querySelector('input[name="_method"]');
        if (method) method.remove();
        deleteBtn.classList.add('hidden');
        document.getElementById('selected-dates').innerHTML = '';
        activateTab('daily');
        escalaModal.classList.remove('hidden');
        initCalendar();
    });
    document.getElementById('escala-cancel').addEventListener('click', () => {
        escalaModal.classList.add('hidden');
    });

    if (applyYear) {
        escalaForm.addEventListener('submit', () => {
            if (!tabRecurring.classList.contains('hidden') && applyYear.checked) {
                const year = escalaForm.querySelector('[name="year"]').value;
                const repeatUntil = escalaForm.querySelector('[name="repeat_until"]');
                const repeatWeeks = escalaForm.querySelector('[name="repeat_weeks"]');
                if (repeatUntil) repeatUntil.value = `${year}-12-31`;
                if (repeatWeeks) repeatWeeks.value = '';
            }
        });
    }

    document.querySelectorAll('.escala-card').forEach(card => {
        card.addEventListener('dblclick', () => {
            escalaForm.reset();
            deleteBtn.classList.remove('hidden');
            const id = card.dataset.id;
            escalaForm.action = `${window.location.pathname}/` + id;
            let method = escalaForm.querySelector('input[name="_method"]');
            if (!method) {
                method = document.createElement('input');
                method.type = 'hidden';
                method.name = '_method';
                escalaForm.appendChild(method);
            }
            method.value = 'PUT';
            escalaForm.querySelector('[name="profissional_id"]').value = card.dataset.profissional;
            escalaForm.querySelector('[name="hora_inicio"]').value = card.dataset.horaInicio;
            escalaForm.querySelector('[name="hora_fim"]').value = card.dataset.horaFim;
            escalaForm.querySelector('[name="cadeira_id"]').value = card.dataset.cadeira;
            const date = card.dataset.date;
            document.getElementById('selected-dates').innerHTML = '';
            const monthInput = document.getElementById('calendar-month-input');
            monthInput.value = date.slice(0,7);
            escalaForm.querySelector('[name="year"]').value = date.slice(0,4);
            escalaForm.querySelector('[name="month"]').value = parseInt(date.slice(5,7),10);
            activateTab('daily');
            escalaModal.classList.remove('hidden');
            initCalendar([date], true);
        });
    });

    deleteBtn.addEventListener('click', () => {
        const id = escalaForm.action.split('/').pop();
        if (confirm('Tem certeza que deseja excluir esta escala?')) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = escalaForm.action;
            form.innerHTML = `<input type="hidden" name="_token" value="${document.querySelector('meta[name=csrf-token]').content}"><input type="hidden" name="_method" value="DELETE">`;
            ['clinic_id','year','month'].forEach(name => {
                const input = escalaForm.querySelector(`[name="${name}"]`);
                if (input) {
                    const copy = document.createElement('input');
                    copy.type = 'hidden';
                    copy.name = name;
                    copy.value = input.value;
                    form.appendChild(copy);
                }
            });
            document.body.appendChild(form);
            form.submit();
        }
    });

    function initCalendar(selected = [], single = false) {
        const monthInput = document.getElementById('calendar-month-input');
        const monthLabel = document.getElementById('calendar-month-label');
        const tableContainer = document.getElementById('calendar-table');
        const prevBtn = document.getElementById('prev-month');
        const nextBtn = document.getElementById('next-month');
        let current = monthInput.value ? new Date(monthInput.value + '-01') : new Date();

        function render() {
            monthInput.value = current.toLocaleDateString('en-CA').slice(0,7);
            monthLabel.textContent = current.toLocaleString('pt-BR', { month: 'long', year: 'numeric' });
            const [y,m] = monthInput.value.split('-');
            escalaForm.querySelector('[name="year"]').value = y;
            escalaForm.querySelector('[name="month"]').value = parseInt(m,10);

            const first = new Date(current.getFullYear(), current.getMonth(), 1);
            const start = new Date(first);
            start.setDate(start.getDate() - ((start.getDay()+6)%7));
            const end = new Date(current.getFullYear(), current.getMonth()+1, 0);
            const last = new Date(end);
            last.setDate(last.getDate() + (7 - ((end.getDay()+6)%7) -1));

            const weekDays = ['Seg','Ter','Qua','Qui','Sex','Sab','Dom'];
            let html = '<table class="w-full border text-center text-sm"><thead><tr>';
            weekDays.forEach(d => html += `<th class="border px-1 py-1">${d}</th>`);
            html += '</tr></thead><tbody></tbody></table>';
            tableContainer.innerHTML = html;

            const tbody = tableContainer.querySelector('tbody');
            let d = new Date(start);
            while (d <= last) {
                if (d.getDay() === 1) tbody.appendChild(document.createElement('tr'));
                const row = tbody.lastElementChild;
                const td = document.createElement('td');
                td.className = 'border px-1 py-1';
                const btn = document.createElement('button');
                btn.type = 'button';
                btn.dataset.date = d.toLocaleDateString('en-CA');
                btn.textContent = d.getDate();
                btn.className = 'day-btn w-full rounded ' + (d.getMonth() === current.getMonth() ? '' : 'text-gray-400');
                td.appendChild(btn);
                row.appendChild(td);
                d.setDate(d.getDate()+1);
            }

            tbody.querySelectorAll('.day-btn').forEach(btn => {
                btn.addEventListener('click', () => toggleDate(btn));
                if (selected.includes(btn.dataset.date)) {
                    toggleDate(btn, true);
                }
            });
        }

        function toggleDate(btn, force = false) {
            const wrapper = document.getElementById('selected-dates');
            const date = btn.dataset.date;
            if (single) {
                wrapper.innerHTML = '';
                tableContainer.querySelectorAll('.day-btn').forEach(b => {
                    b.classList.remove('bg-emerald-400','text-white');
                });
            }
            if (!force) {
                btn.classList.toggle('bg-emerald-400');
                btn.classList.toggle('text-white');
            } else {
                btn.classList.add('bg-emerald-400','text-white');
            }
            if (btn.classList.contains('bg-emerald-400')) {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = single ? 'data' : 'datas[]';
                input.value = date;
                input.dataset.date = date;
                wrapper.appendChild(input);
            } else {
                const input = wrapper.querySelector(`input[data-date="${date}"]`);
                if (input) input.remove();
            }
        }

        prevBtn.onclick = () => { current.setMonth(current.getMonth() - 1); render(); };
        nextBtn.onclick = () => { current.setMonth(current.getMonth() + 1); render(); };
        render();
    }
</script>
@endpush
@endsection
