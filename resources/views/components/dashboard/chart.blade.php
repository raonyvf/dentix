@props(['title', 'chartId' => null])

@php
    $id = $chartId ?: \Illuminate\Support\Str::slug($title);
@endphp

<div class="bg-white rounded-lg shadow p-4">
    <div class="mb-2 flex items-center justify-between">
        <h3 class="text-lg font-semibold text-gray-700">{{ $title }}</h3>
        {{ $header ?? '' }}
    </div>
    <div class="h-64">
        <canvas id="{{ $id }}" class="w-full h-full"></canvas>
    </div>
    {{ $slot ?? '' }}
</div>
