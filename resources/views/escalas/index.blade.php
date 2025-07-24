@extends('layouts.app')

@section('content')
@include('partials.breadcrumbs', ['crumbs' => [
    ['label' => 'Dashboard', 'url' => route('admin.index')],
    ['label' => 'Escalas de Trabalho']
]])
<div class="mb-6 flex justify-between items-center">
    <h1 class="text-2xl font-bold">Escalas de Trabalho</h1>
    <button id="open-modal" class="py-2 px-4 bg-blue-600 text-white rounded">+ Adicionar Escala</button>
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
        <input type="date" name="week" value="{{ $week->format('Y-m-d') }}" class="border rounded px-2 py-1" onchange="this.form.submit()">
    </div>
</form>
<div class="overflow-auto bg-white rounded shadow">
    <table class="min-w-full text-sm">
        <thead class="bg-gray-50">
            <tr>
                <th class="p-2 text-left">Cadeira</th>
                @foreach($dias as $d)
                    <th class="p-2 text-left capitalize">{{ $d }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @foreach($cadeiras as $cadeira)
                <tr class="border-t">
                    <td class="p-2 whitespace-nowrap">{{ $cadeira->nome }}</td>
                    @foreach($dias as $d)
                        <td class="p-2 align-top w-40">
                            @php $items = $schedules[$cadeira->id][$d] ?? collect(); @endphp
                            @foreach($items as $it)
                                <div class="mb-1">
                                    <span class="font-medium">{{ $it->profissional->person->first_name }} {{ $it->profissional->person->last_name }}</span>
                                    <span class="text-xs text-gray-500">{{ $it->hora_inicio }}-{{ $it->hora_fim }}</span>
                                </div>
                            @endforeach
                        </td>
                    @endforeach
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
<div id="escala-modal" class="fixed inset-0 bg-black/50 hidden items-center justify-center z-50">
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
