@props(['label', 'numero', 'mes', 'active' => false, 'past' => false])
@php
    $classes = 'flex flex-col items-center p-2 rounded cursor-pointer text-xs flex-1 text-center';
    if ($active) {
        $classes .= ' bg-black text-white';
    } elseif ($past) {
        $classes .= ' text-gray-400';
    } else {
        $classes .= ' text-gray-700';
    }
@endphp
<div {{ $attributes->merge(['class' => $classes]) }}>
    <span class="uppercase">{{ $label }}</span>
    <span class="font-semibold">{{ $numero }}</span>
    <span>{{ $mes }}</span>
</div>
