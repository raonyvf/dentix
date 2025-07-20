@props(['label', 'value', 'icon', 'color' => 'bg-blue-500'])
<div {{ $attributes->merge(['class' => "flex items-center p-4 text-white rounded-lg $color"]) }}>
    <div class="mr-4">
        {!! $icon !!}
    </div>
    <div>
        <p class="text-sm">{{ $label }}</p>
        <p class="text-xl font-semibold">{{ $value }}</p>
    </div>
</div>
