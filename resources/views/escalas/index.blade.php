@extends('layouts.app')

@section('content')
@include('partials.breadcrumbs', ['crumbs' => [
    ['label' => 'Dashboard', 'url' => route('admin.index')],
    ['label' => 'Escalas de Trabalho']
]])
<div class="mb-6 flex flex-wrap justify-between items-center gap-4">
    <h1 class="text-2xl font-bold">Escalas de Trabalho</h1>
    <button id="open-modal" class="flex items-center gap-1 px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
        </svg>
        <span>Adicionar Escala</span>
    </button>
</div>
<form method="GET" class="flex flex-wrap gap-4 mb-4">
    <div>
        <label class="block text-sm font-medium mb-1">Clínica</label>
        <select name="clinic_id" class="border rounded px-2 py-1" onchange="this.form.submit()">
            @foreach($clinics as $c)
                <option value="{{ $c->id }}" @selected($clinicId==$c->id)>{{ $c->nome }}</option>
            @endforeach
        </select>
    </div>
    <div>
        <label class="block text-sm font-medium mb-1">Visualização</label>
        <select name="view" class="border rounded px-2 py-1" onchange="this.form.submit()">
            <option value="week" @selected($view==='week')>Semanal</option>
            <option value="month" @selected($view==='month')>Mensal</option>
        </select>
    </div>
    @if($view==='month')
        <div>
            <label class="block text-sm font-medium mb-1">Mês</label>
            <select name="month" class="border rounded px-2 py-1" onchange="this.form.submit()">
                @foreach($mesesDisponiveis as $mes)
                    <option value="{{ $mes->format('Y-m') }}" @selected($mes->equalTo($month))>{{ $mes->translatedFormat('F Y') }}</option>
                @endforeach
            </select>
        </div>
    @else
        <div>
            <label class="block text-sm font-medium mb-1">Semana</label>
            <select name="week" class="border rounded px-2 py-1" onchange="this.form.submit()">
                @foreach($semanasDisponiveis as $sem)
                    <option value="{{ $sem->format('Y-m-d') }}" @selected($sem->equalTo($week))>{{ $sem->format('d/m/Y') }}</option>
                @endforeach
            </select>
        </div>
    @endif
</form>
@if($view==='month')
    @foreach($weeks as $w)
        <h2 class="mt-6 font-semibold">Semana de {{ $w->format('d/m/Y') }}</h2>
        <div class="overflow-x-auto bg-white rounded shadow mb-6">
            <table class="table-fixed w-full text-sm border">
                <thead>
                    <tr>
                        <th class="w-32 px-2 py-1 bg-white font-semibold border border-gray-200">Cadeira</th>
                        @php $weekStart = $w; @endphp
                        @foreach($dias as $d)
                            <th class="px-2 py-1 text-center font-semibold bg-white capitalize border border-gray-200" style="width:14.28%">
                                {{ ucfirst($d) }}<br>
                                <span class="text-xs text-gray-500">{{ $weekStart->copy()->addDays($loop->index)->format('d/m') }}</span>
                            </th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @foreach($cadeiras as $cadeira)
                        <tr>
                            <td class="w-32 px-2 py-1 font-semibold bg-gray-50 border border-gray-200">{{ $cadeira->nome }}</td>
                            @foreach($dias as $d)
                                @php $items = $escalas[$w->toDateString()][$cadeira->id][$d] ?? collect(); @endphp
                                <td class="px-2 py-2 border border-gray-200 align-top" style="width:14.28%">
                                    @forelse($items as $it)
                                        <div class="mb-2 p-2 rounded bg-emerald-50 text-sm">
                                            <div class="font-semibold whitespace-nowrap overflow-hidden text-ellipsis">{{ optional($it->profissional->person)->first_name }} {{ optional($it->profissional->person)->last_name }}</div>
                                            <div>{{ $it->hora_inicio }} – {{ $it->hora_fim }}</div>
                                            <div class="text-xs text-gray-600">{{ optional($it->profissional->user)->especialidade ?? $it->profissional->cargo }}</div>
                                            <div class="mt-1 h-2 rounded bg-emerald-400 w-full"></div>
                                        </div>
                                    @empty
                                        <span class="text-sm text-gray-400">Livre</span>
                                    @endforelse
                                </td>
                            @endforeach
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endforeach
@else
    <div class="overflow-x-auto bg-white rounded shadow">
        <table class="table-fixed w-full text-sm border">
            <thead>
                <tr>
                    <th class="w-32 px-2 py-1 bg-white font-semibold border border-gray-200">Cadeira</th>
                    @php $weekStart = $week; @endphp
                    @foreach($dias as $d)
                        <th class="px-2 py-1 text-center font-semibold bg-white capitalize border border-gray-200" style="width:14.28%">
                            {{ ucfirst($d) }}<br>
                            <span class="text-xs text-gray-500">{{ $weekStart->copy()->addDays($loop->index)->format('d/m') }}</span>
                        </th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @foreach($cadeiras as $cadeira)
                    <tr>
                        <td class="w-32 px-2 py-1 font-semibold bg-gray-50 border border-gray-200">{{ $cadeira->nome }}</td>
                        @foreach($dias as $d)
                            @php $items = $escalas[$cadeira->id][$d] ?? collect(); @endphp
                            <td class="px-2 py-2 border border-gray-200 align-top" style="width:14.28%">
                                @forelse($items as $it)
                                    <div class="mb-2 p-2 rounded bg-emerald-50 text-sm">
                                        <div class="font-semibold whitespace-nowrap overflow-hidden text-ellipsis">{{ optional($it->profissional->person)->first_name }} {{ optional($it->profissional->person)->last_name }}</div>
                                        <div>{{ $it->hora_inicio }} – {{ $it->hora_fim }}</div>
                                        <div class="text-xs text-gray-600">{{ optional($it->profissional->user)->especialidade ?? $it->profissional->cargo }}</div>
                                        <div class="mt-1 h-2 rounded bg-emerald-400 w-full"></div>
                                    </div>
                                @empty
                                    <span class="text-sm text-gray-400">Livre</span>
                                @endforelse
                            </td>
                        @endforeach
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endif
<div id="escala-modal" class="fixed inset-0 bg-black/50 hidden flex items-center justify-center z-50">
    <div class="bg-white rounded p-4 w-96">
        <form method="POST" action="{{ route('escalas.store') }}" class="space-y-4">
            @csrf
            <input type="hidden" name="clinic_id" value="{{ $clinicId }}">
            <input type="hidden" name="view" value="{{ $view }}">
            <div>
                <label class="block text-sm mb-1">Profissional</label>
                <select name="profissional_id" class="w-full border rounded px-2 py-1">
                    @foreach($dentistas as $d)
                        <option value="{{ $d->id }}">{{ $d->person->first_name }} {{ $d->person->last_name }}</option>
                    @endforeach
                </select>
            </div>
            @if($view==='month')
                <input type="hidden" name="month" id="calendar-month-input" value="{{ $month->format('Y-m') }}">
            @else
                <input type="hidden" name="semana" value="{{ $week->format('Y-m-d') }}">
                <input type="hidden" id="calendar-month-input" value="{{ $week->format('Y-m') }}">
            @endif
            <div class="mb-2 flex items-center justify-between">
                <button type="button" id="prev-month" class="px-2">&#60;</button>
                <span id="calendar-month-label" class="font-semibold"></span>
                <button type="button" id="next-month" class="px-2">&#62;</button>
            </div>
            <div id="calendar-table" class="mb-2 text-sm"></div>
            <div id="selected-dates"></div>
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
                <select name="cadeira_id" class="w-full border rounded px-2 py-1">
                    @foreach($cadeiras as $c)
                        <option value="{{ $c->id }}">{{ $c->nome }}</option>
                    @endforeach
                </select>
            </div>
            <div class="text-right space-x-2">
                <button type="button" id="escala-cancel" class="px-3 py-1 border rounded">Cancelar</button>
                <button class="px-3 py-1 bg-blue-600 text-white rounded">Salvar</button>
            </div>
        </form>
    </div>
</div>
@push('scripts')
<script>
    const escalaModal = document.getElementById('escala-modal');
    document.getElementById('open-modal').addEventListener('click', () => {
        escalaModal.classList.remove('hidden');
        initCalendar();
    });
    document.getElementById('escala-cancel').addEventListener('click', () => {
        escalaModal.classList.add('hidden');
    });

    function initCalendar() {
        const monthInput = document.getElementById('calendar-month-input');
        const monthLabel = document.getElementById('calendar-month-label');
        const tableContainer = document.getElementById('calendar-table');
        const prevBtn = document.getElementById('prev-month');
        const nextBtn = document.getElementById('next-month');
        let current = monthInput.value ? new Date(monthInput.value + '-01') : new Date();

        function render() {
            monthInput.value = current.toISOString().slice(0,7);
            monthLabel.textContent = current.toLocaleString('pt-BR', { month: 'long', year: 'numeric' });

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
                btn.dataset.date = d.toISOString().slice(0,10);
                btn.textContent = d.getDate();
                btn.className = 'day-btn w-full rounded ' + (d.getMonth() === current.getMonth() ? '' : 'text-gray-400');
                td.appendChild(btn);
                row.appendChild(td);
                d.setDate(d.getDate()+1);
            }

            tbody.querySelectorAll('.day-btn').forEach(btn => {
                btn.addEventListener('click', () => toggleDate(btn));
            });
        }

        function toggleDate(btn) {
            const wrapper = document.getElementById('selected-dates');
            const date = btn.dataset.date;
            btn.classList.toggle('bg-emerald-400');
            btn.classList.toggle('text-white');
            if (btn.classList.contains('bg-emerald-400')) {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'datas[]';
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
