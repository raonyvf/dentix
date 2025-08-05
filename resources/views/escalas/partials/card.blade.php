@php
    $diaSemana = $diaSemana ?? null;
    $horarioClinica = $clinic?->horarios->firstWhere('dia_semana', $diaSemana);
    $totalMinutos = 0;
    if ($horarioClinica) {
        $inicioClinica = \Carbon\Carbon::parse($horarioClinica->hora_inicio);
        $fimClinica = \Carbon\Carbon::parse($horarioClinica->hora_fim);
        $totalMinutos = $inicioClinica->diffInMinutes($fimClinica);
    }
    $inicio = \Carbon\Carbon::parse($it->hora_inicio);
    $fim = \Carbon\Carbon::parse($it->hora_fim);
    $duracao = $inicio->diffInMinutes($fim);
    $percentual = $totalMinutos > 0 ? ($duracao / $totalMinutos) * 100 : 0;
@endphp
<div class="mb-2 p-2 rounded bg-emerald-50 text-sm">
    <div class="font-semibold whitespace-nowrap overflow-hidden text-ellipsis">
        {{ optional($it->profissional->pessoa)->primeiro_nome }} {{ optional($it->profissional->pessoa)->ultimo_nome }}
    </div>
    <div>{{ $it->hora_inicio }} – {{ $it->hora_fim }}</div>
    <div class="text-xs text-gray-600">
        {{ optional($it->profissional->usuario)->especialidade ?? $it->profissional->cargo }}
    </div>
    <div class="mt-1 h-2 rounded bg-emerald-400" style="width: {{ $percentual }}%"></div>
</div>
