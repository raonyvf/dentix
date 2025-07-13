<div x-data="{ show: true }" x-show="show" x-transition class="mb-4 flex items-center justify-between rounded-lg border border-green-200 bg-green-50 p-4 text-sm text-green-700">
    <div class="flex items-center">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mr-2 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
        </svg>
        <span>{{ $slot }}</span>
    </div>
    <button @click="show = false" class="text-green-700 hover:text-green-900">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
        </svg>
    </button>
</div>
