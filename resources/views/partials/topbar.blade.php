<header class="flex items-center h-16 bg-white border-b shadow px-4">
    <button @click="sidebarCollapsed = !sidebarCollapsed" class="p-2 text-gray-600 hover:text-gray-900">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
        </svg>
    </button>
    <span class="ml-4 text-lg font-semibold">{{ config('app.name') }}</span>
</header>
