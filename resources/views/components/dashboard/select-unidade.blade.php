@props(['options' => [], 'label' => 'Todas as Unidades'])
<div class="relative" x-data="{ open: false }">
    <button @click="open = !open" type="button" class="flex items-center px-4 py-2 bg-white border rounded shadow-sm text-sm hover:bg-gray-50">
        <span class="mr-2">{{ $label }}</span>
        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
        </svg>
    </button>
    <div x-show="open" @click.away="open = false" x-cloak class="absolute right-0 mt-2 w-40 bg-white border rounded shadow">
        @foreach($options as $option)
            <a href="#" class="block px-4 py-2 text-sm hover:bg-gray-100">{{ $option }}</a>
        @endforeach
    </div>
</div>
