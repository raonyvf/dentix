@extends('layouts.app')

@section('content')
@include('partials.breadcrumbs', ['crumbs' => [
    ['label' => 'Dashboard', 'url' => route('admin.index')],
    ['label' => 'Escalas de Trabalho']
]])
<div class="mb-6 flex flex-wrap justify-between items-center gap-4">
    <h1 class="text-2xl font-bold">Escalas de Trabalho</h1>
    <button id="open-modal" class="flex items-center gap-1 px-4 py-2 bg-emerald-400 text-white rounded hover:bg-emerald-500">
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
        <label class="block text-sm font-medium mb-1">Semana</label>
        <select name="week" class="border rounded px-2 py-1" onchange="this.form.submit()">
            @foreach($semanasDisponiveis as $sem)
                <option value="{{ $sem->format('Y-m-d') }}" @selected($sem->equalTo($week))>{{ $sem->format('d/m/Y') }}</option>
            @endforeach
        </select>
    </div>
</form>

<div class="overflow-x-auto bg-white rounded shadow">
    <table class="min-w-full text-sm">
        <thead>
            <tr>
                <th class="p-2 bg-gray-50 w-24 text-left sticky left-0 z-10">Cadeira</th>
                @foreach($dias as $d)
                    <th class="p-2 bg-gray-50 text-left capitalize min-w-[576px]">{{ ucfirst($d) }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @foreach($cadeiras as $cadeira)
                <tr class="border-t">

                    <td class="bg-gray-50 w-24 p-2 whitespace-nowrap sticky left-0">{{ $cadeira->nome }}</td>

                    @foreach($dias as $d)
                        @php $items = $escalas[$cadeira->id][$d] ?? collect(); @endphp
                        <td class="min-w-[576px] align-top p-2">
                            @if($items->isNotEmpty())
                                @php
                                    $sorted = $items->sortBy('hora_inicio')->values();
                                    $conflict = false;
                                    for($i=0;$i<$sorted->count();$i++){
                                        for($j=$i+1;$j<$sorted->count();$j++){
                                            if(!($sorted[$i]->hora_fim <= $sorted[$j]->hora_inicio || $sorted[$i]->hora_inicio >= $sorted[$j]->hora_fim)){
                                                $conflict = true; break 2;
                                            }
                                        }
                                    }
                                @endphp
                                @foreach($items as $it)
                                    @php
                                        $inicio = \Carbon\Carbon::parse($it->hora_inicio);
                                        $fim = \Carbon\Carbon::parse($it->hora_fim);
                                        $inicioMin = $inicio->hour*60 + $inicio->minute;
                                        $fimMin = $fim->hour*60 + $fim->minute;
                                        $left = $inicioMin / 1440 * 100;
                                        $width = ($fimMin - $inicioMin) / 1440 * 100;
                                    @endphp
                                    <div class="mb-2 p-2 bg-emerald-50 border border-emerald-200 rounded">
                                        <div class="flex items-center justify-between">
                                            <span class="font-medium">{{ optional($it->profissional->person)->first_name }} {{ optional($it->profissional->person)->last_name }}</span>
                                            @if($conflict)
                                                <svg class="w-4 h-4 text-red-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M5.07 19h13.86a2 2 0 001.73-3l-6.93-11a2 2 0 00-3.46 0l-6.93 11a2 2 0 001.73 3z" />
                                                </svg>
                                            @endif
                                        </div>
                                        <div class="text-xs">{{ $it->hora_inicio }} - {{ $it->hora_fim }}</div>
                                        @if(optional($it->profissional->user)->especialidade)
                                            <div class="text-xs text-gray-500">{{ optional($it->profissional->user)->especialidade }}</div>
                                        @endif
                                        <div class="relative h-3 bg-emerald-100 rounded mt-1 overflow-hidden">
                                            <div class="absolute inset-0 pointer-events-none" style="background-image:repeating-linear-gradient(to right,#d1d5db 0,#d1d5db 1px,transparent 1px,transparent calc(100%/48));"></div>
                                            <div class="absolute top-0 h-full bg-emerald-400 rounded" style="left:{{ $left }}%; width:{{ $width }}%;"></div>
                                        </div>
                                    </div>
                                @endforeach
                                <div class="text-xs text-gray-500">{{ $items->count() }} escala{{ $items->count() > 1 ? 's' : '' }}</div>
                            @else
                                <div class="text-xs text-gray-400">Livre</div>
                            @endif
                        </td>
                    @endforeach
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
<div id="escala-modal" class="fixed inset-0 bg-black/50 hidden flex items-center justify-center z-50">
    <div class="bg-white rounded p-4 w-80">
        <form method="POST" action="{{ route('escalas.store') }}" class="space-y-4">
            @csrf
            <input type="hidden" name="clinic_id" value="{{ $clinicId }}">
            <input type="hidden" name="semana" value="{{ $week->format('Y-m-d') }}">
            <div>
                <label class="block text-sm mb-1">Profissional</label>
                <select name="profissional_id" class="w-full border rounded px-2 py-1">
                    @foreach($dentistas as $d)
                        <option value="{{ $d->id }}">{{ $d->person->first_name }} {{ $d->person->last_name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm mb-1">Cadeira</label>
                <select name="cadeira_id" class="w-full border rounded px-2 py-1">
                    @foreach($cadeiras as $c)
                        <option value="{{ $c->id }}">{{ $c->nome }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm mb-1">Dias da semana</label>
                <select name="dias[]" multiple class="w-full border rounded px-2 py-1">
                    @foreach($dias as $d)
                        <option value="{{ $d }}">{{ ucfirst($d) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex gap-2">
                <div class="flex-1">
                    <label class="block text-sm mb-1">Início</label>
                    <input type="time" name="hora_inicio" class="w-full border rounded px-2 py-1">
                </div>
                <div class="flex-1">
                    <label class="block text-sm mb-1">Fim</label>
                    <input type="time" name="hora_fim" class="w-full border rounded px-2 py-1">
                </div>
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
    document.getElementById('open-modal').addEventListener('click', () => {
        document.getElementById('escala-modal').classList.remove('hidden');
    });
    document.getElementById('escala-cancel').addEventListener('click', () => {
        document.getElementById('escala-modal').classList.add('hidden');
    });
</script>
@endpush
@endsection
