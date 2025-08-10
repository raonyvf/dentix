@props(['paciente', 'tipo', 'contato', 'status' => 'vago'])
@php
    $color = match($status) {
        'confirmado' => 'bg-green-100 text-green-700',
        'cancelado' => 'bg-red-100 text-red-700',
        'vago' => 'bg-gray-100 text-gray-700',
        default => 'bg-gray-100 text-gray-700',
    };
@endphp
<div {{ $attributes->merge(['class' => "rounded p-2 text-xs $color"]) }}>
    <div class="font-semibold">{{ $paciente }}</div>
    <div>{{ $tipo }}</div>
    <div class="text-[10px]">{{ $contato }}</div>
</div>
