@props([
    'paciente',
    'inicio' => null,
    'fim' => null,
    'observacao' => '',
    'status' => 'pendente'
])
@php
    $statusLabel = $status === 'confirmado'
        ? 'Confirmado'
        : ($status === 'cancelado' ? 'Cancelado' : 'Sem confirmação');
    $color = $status === 'confirmado'
        ? 'bg-green-100 text-green-700'
        : 'bg-gray-100 text-gray-700';
@endphp
{{-- Additional data-* attributes are forwarded to the root div --}}
<div {{ $attributes->merge(['class' => "rounded p-2 text-xs border border-green-800 $color"]) }}>
    <div class="font-bold text-sm">{{ $paciente }}</div>
    @if($inicio && $fim)
        <div>{{ $inicio }} - {{ $fim }}</div>
    @endif
    <div>{{ $observacao }}</div>
    <div>{{ $statusLabel }}</div>
</div>
