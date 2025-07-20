@props(['title', 'value', 'icon' => null, 'comparison' => null, 'link' => '#'])
<div class="p-4 bg-white rounded-lg shadow flex flex-col justify-between">
    <div class="flex justify-between items-start">
        <div>
            <p class="text-sm text-gray-500">{{ $title }}</p>
            <p class="mt-2 text-3xl font-semibold text-gray-700">{{ $value }}</p>
        </div>
        @if ($icon)
            <div class="text-gray-400">
                {!! $icon !!}
            </div>
        @endif
    </div>
    @if($comparison)
        <p class="mt-2 text-xs text-gray-500">{{ $comparison }}</p>
    @endif
    <div class="mt-3">
        <a href="{{ $link }}" class="text-sm text-primary hover:underline">Ver detalhes</a>
    </div>
</div>
