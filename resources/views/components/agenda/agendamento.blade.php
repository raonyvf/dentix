@props([
    'paciente',
    'inicio' => null,
    'fim' => null,
    'observacao' => '',
    'status' => 'pendente'
])
@php
    $statusLabel = match ($status) {
        'confirmado' => 'Confirmado',
        'pendente' => 'Pendente',
        'cancelado' => 'Cancelado',
        default => 'Sem confirmação',
    };
    $color = match ($status) {
        'confirmado' => 'bg-green-100 text-green-700 border-green-800',
        'pendente' => 'bg-yellow-100 text-yellow-700 border-yellow-800',
        'cancelado' => 'bg-red-100 text-red-700 border-red-800',
        default => 'bg-gray-100 text-gray-700 border-gray-800',
    };
@endphp
{{-- Additional data-* attributes are forwarded to the root div --}}
<div {{ $attributes->merge(['class' => "rounded p-2 text-xs border $color"]) }}>
    <div class="font-bold text-sm">{{ $paciente }}</div>
    @if($inicio && $fim)
        <div>{{ $inicio }} - {{ $fim }}</div>
    @endif
    <div>{{ $observacao }}</div>
    <div>{{ $statusLabel }}</div>
</div>
