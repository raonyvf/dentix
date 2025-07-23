@props(['title', 'open' => false])
<div x-data="{ open: @json($open) }" class="rounded-sm border border-stroke bg-gray-50">
    <button type="button" @click="open = !open" class="w-full flex justify-between items-center p-4">
        <span class="text-sm font-medium text-gray-700">{{ $title }}</span>
        <svg class="w-4 h-4 transform transition-transform" :class="{ 'rotate-180': open }" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
        </svg>
    </button>
    <div x-show="open" x-collapse x-cloak class="p-4 border-t border-stroke">
        {{ $slot }}
    </div>
</div>
