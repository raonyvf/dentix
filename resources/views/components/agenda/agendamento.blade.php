@props(['paciente', 'observacao' => '', 'status' => 'pendente'])
@php
    $color = $status === 'confirmado'
        ? 'bg-green-100 text-green-700'
        : 'bg-gray-100 text-gray-700';
@endphp
<div {{ $attributes->merge(['class' => "rounded p-2 text-xs $color"]) }}>
    <div class="font-semibold">{{ $paciente }}</div>
    <div>{{ $observacao }}</div>
</div>
