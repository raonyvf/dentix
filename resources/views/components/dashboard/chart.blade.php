@props(['title'])
<div class="bg-white rounded-lg shadow p-4">
    <div class="mb-2 flex items-center justify-between">
        <h3 class="text-lg font-semibold text-gray-700">{{ $title }}</h3>
        {{ $header ?? '' }}
    </div>
    <div class="h-64">
        <canvas></canvas>
    </div>
</div>
