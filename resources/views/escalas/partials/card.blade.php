@php
    $diaSemana = $diaSemana ?? null;
    $horarioClinica = $clinic?->horarios->firstWhere('dia_semana', $diaSemana);

    $inicio = \Carbon\Carbon::parse($it->hora_inicio);
    $fim = \Carbon\Carbon::parse($it->hora_fim);

    $totalMinutos = 0;
    $offsetPercentual = 0;
    if ($horarioClinica) {
        $inicioClinica = \Carbon\Carbon::parse($horarioClinica->hora_inicio);
        $fimClinica = \Carbon\Carbon::parse($horarioClinica->hora_fim);
        $totalMinutos = $inicioClinica->diffInMinutes($fimClinica);
        $offsetMinutos = $inicioClinica->diffInMinutes($inicio);
        $offsetPercentual = $totalMinutos > 0 ? ($offsetMinutos / $totalMinutos) * 100 : 0;
    }

    $duracao = $inicio->diffInMinutes($fim);
    $percentual = $totalMinutos > 0 ? ($duracao / $totalMinutos) * 100 : 0;
@endphp
<div class="mb-2 p-2 rounded bg-emerald-50 text-sm escala-card relative cursor-pointer" data-id="{{ $it->id }}" data-profissional="{{ $it->profissional_id }}" data-hora-inicio="{{ $it->hora_inicio }}" data-hora-fim="{{ $it->hora_fim }}" data-cadeira="{{ $it->cadeira_id }}" data-date="{{ \Carbon\Carbon::parse($it->semana)->addDays($it->dia_semana - 1)->format('Y-m-d') }}">
    <div class="font-semibold whitespace-nowrap overflow-hidden text-ellipsis">
        {{ optional($it->profissional->pessoa)->primeiro_nome }} {{ optional($it->profissional->pessoa)->ultimo_nome }}
    </div>
    <div>{{ $it->hora_inicio }} – {{ $it->hora_fim }}</div>
    <div class="text-xs text-gray-600">
        {{ optional($it->profissional->usuario)->especialidade ?? $it->profissional->cargo }}
    </div>
    <div class="mt-1 h-2 rounded bg-emerald-400" style="width: {{ $percentual }}%; margin-left: {{ $offsetPercentual }}%"></div>
</div>
