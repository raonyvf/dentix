@props(['time'])
<div {{ $attributes->merge(['class' => 'p-2 text-right text-xs text-gray-500 whitespace-nowrap']) }}>
    {{ $time }}
</div>
