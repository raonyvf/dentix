@props(['title', 'chartId' => null, 'height' => 'h-64'])

@php
    $id = $chartId ?: \Illuminate\Support\Str::slug($title);
@endphp

<div class="bg-white rounded-lg shadow p-4">
    <div class="mb-2 flex items-center justify-between">
        <h3 class="text-lg font-semibold text-gray-700">{{ $title }}</h3>
        {{ $header ?? '' }}
    </div>
    <div class="{{ $height }}">
        <canvas id="{{ $id }}" class="w-full h-full"></canvas>
    </div>
    {{ $slot ?? '' }}
</div>