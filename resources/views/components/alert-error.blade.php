<div x-data="{ show: true }" x-show="show" x-transition class="mb-4 flex items-center justify-between rounded-lg border border-red-200 bg-red-50 p-4 text-sm text-red-700">
    <div class="flex items-center">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mr-2 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M4.93 4.93l1.42 1.42A9 9 0 1121 12h0a9 9 0 11-7.07-7.07L4.93 4.93z" />
        </svg>
        <span>{{ $slot }}</span>
    </div>
    <button @click="show = false" class="text-red-700 hover:text-red-900">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
        </svg>
    </button>
</div>
