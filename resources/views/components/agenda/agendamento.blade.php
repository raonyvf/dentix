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
    $borderColor = match ($status) {
        'confirmado' => 'rgb(52 211 153 / var(--tw-bg-opacity, 1))',
        'pendente' => 'rgb(250 204 21 / var(--tw-bg-opacity, 1))',
        'cancelado' => 'rgb(248 113 113 / var(--tw-bg-opacity, 1))',
        default => 'rgb(156 163 175 / var(--tw-bg-opacity, 1))',
    };
    $color = match ($status) {
        'confirmado' => 'bg-green-100 text-green-700',
        'pendente' => 'bg-yellow-100 text-yellow-700',
        'cancelado' => 'bg-red-100 text-red-700',
        default => 'bg-gray-100 text-gray-700',
    };
@endphp
{{-- Additional data-* attributes are forwarded to the root div --}}
<div {{ $attributes->merge(['class' => "rounded p-2 text-xs $color", 'style' => "border: 2px solid $borderColor"]) }}>
    <div class="font-bold text-sm">{{ $paciente }}</div>
    @if($inicio && $fim)
        <div>{{ $inicio }} - {{ $fim }}</div>
    @endif
    <div>{{ $observacao }}</div>
    <div>{{ $statusLabel }}</div>
</div>
