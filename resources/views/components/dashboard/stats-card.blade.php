@props(['title', 'value', 'icon' => null, 'comparison' => null, 'valueClass' => 'text-black'])
<div class="rounded-sm border border-stroke bg-white p-5 shadow">
    <div class="flex items-center justify-between">
        <div>
            <p class="text-sm text-gray-500">{{ $title }}</p>
            <p class="mt-1 text-xl font-bold {{ $valueClass }}">{{ $value }}</p>
        </div>
        @if ($icon)
            <div class="flex h-11 w-11 items-center justify-center rounded-full bg-primary/10 text-primary">
                {!! $icon !!}
            </div>
        @endif
    </div>
    @if ($comparison)
        <p class="mt-2 text-xs text-gray-500">{{ $comparison }}</p>
    @endif
</div>
