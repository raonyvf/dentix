@props(['time'])
<div {{ $attributes->merge(['class' => 'h-full flex items-center justify-end px-2 text-xs text-gray-500 whitespace-nowrap']) }}>
    {{ $time }}
</div>
