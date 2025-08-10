@props(['name', 'active' => false])
@php
    $classes = 'px-4 py-2 rounded border text-sm whitespace-nowrap';
    $classes .= $active ? ' bg-primary text-white' : ' bg-white text-gray-700';
@endphp
<button {{ $attributes->merge(['class' => $classes]) }}>{{ $name }}</button>
