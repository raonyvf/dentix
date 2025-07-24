<div class="flex flex-col h-full" x-data="{ adminOpen: false }">
    <div class="h-16 flex items-center justify-center border-b">
        <span class="text-xl font-bold" x-show="!sidebarCollapsed">Dentix</span>
        <span x-show="sidebarCollapsed" class="text-xl font-bold">DX</span>
    </div>
    @php $isSuperAdmin = Auth::user() && Auth::user()->isSuperAdmin(); @endphp
    <nav class="flex-1 overflow-y-auto py-4">
        @if($isSuperAdmin)
        <a href="{{ route('backend.index') }}" class="flex items-center px-4 py-2 text-gray-700 hover:bg-gray-100" :title="sidebarCollapsed ? 'Backend' : ''">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4c-4.418 0-8 1.79-8 4v8c0 2.21 3.582 4 8 4s8-1.79 8-4V8c0-2.21-3.582-4-8-4z" />
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8c0 2.21 3.582 4 8 4s8-1.79 8-4" />
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16c0 2.21 3.582 4 8 4s8-1.79 8-4" />
            </svg>
            <span class="ml-3" x-show="!sidebarCollapsed">Backend</span>
        </a>
        <a href="{{ route('usuarios-admin.index') }}" class="flex items-center px-4 py-2 text-gray-700 hover:bg-gray-100" :title="sidebarCollapsed ? 'Usuários Admin' : ''">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a4 4 0 00-4-4h-1" />
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 20H2v-2a4 4 0 014-4h1" />
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 8a4 4 0 10-8 0 4 4 0 008 0z" />
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8a4 4 0 11-8 0 4 4 0 018 0z" />
            </svg>
            <span class="ml-3" x-show="!sidebarCollapsed">Usuários Admin</span>
        </a>
        @else
        <a href="{{ route('admin.index') }}" class="flex items-center px-4 py-2 text-gray-700 hover:bg-gray-100" :title="sidebarCollapsed ? 'Dashboard' : ''">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0h6" /></svg>
            <span class="ml-3" x-show="!sidebarCollapsed">Dashboard</span>
        </a>
        <a href="{{ route('pacientes.index') }}" class="flex items-center px-4 py-2 text-gray-700 hover:bg-gray-100" :title="sidebarCollapsed ? 'Pacientes' : ''">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A13.937 13.937 0 0112 15c2.33 0 4.5.533 6.879 1.532M15 11a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
            <span class="ml-3" x-show="!sidebarCollapsed">Pacientes</span>
        </a>
        <a href="{{ route('agenda.index') }}" class="flex items-center px-4 py-2 text-gray-700 hover:bg-gray-100" :title="sidebarCollapsed ? 'Agenda' : ''">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
            <span class="ml-3" x-show="!sidebarCollapsed">Agenda</span>
        </a>
        <a href="#" class="flex items-center px-4 py-2 text-gray-700 hover:bg-gray-100" :title="sidebarCollapsed ? 'Prontuários' : ''">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h7m-7 4h7" /></svg>
            <span class="ml-3" x-show="!sidebarCollapsed">Prontuários</span>
        </a>
        <a href="{{ route('profissionais.index') }}" class="flex items-center px-4 py-2 text-gray-700 hover:bg-gray-100" :title="sidebarCollapsed ? 'Profissionais' : ''">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 7h12a2 2 0 012 2v8a2 2 0 01-2 2H6a2 2 0 01-2-2V9a2 2 0 012-2z" />
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V5a2 2 0 012-2h4a2 2 0 012 2v2" />
            </svg>
            <span class="ml-3" x-show="!sidebarCollapsed">Profissionais</span>
        </a>
        <a href="{{ route('escalas.index') }}" class="flex items-center px-4 py-2 text-gray-700 hover:bg-gray-100" :title="sidebarCollapsed ? 'Escalas de Trabalho' : ''">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
            </svg>
            <span class="ml-3" x-show="!sidebarCollapsed">Escalas de Trabalho</span>
        </a>
        <a href="#" class="flex items-center px-4 py-2 text-gray-700 hover:bg-gray-100" :title="sidebarCollapsed ? 'Estoque' : ''">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V8a1 1 0 00-.553-.894l-7-3.5a1 1 0 00-.894 0l-7 3.5A1 1 0 004 8v5m16 0v5a1 1 0 01-.553.894l-7 3.5a1 1 0 01-.894 0l-7-3.5A1 1 0 014 18v-5m16 0L12 7.5M4 13l8-4.5" /></svg>
            <span class="ml-3" x-show="!sidebarCollapsed">Estoque</span>
        </a>
        <a href="#" class="flex items-center px-4 py-2 text-gray-700 hover:bg-gray-100" :title="sidebarCollapsed ? 'Financeiro' : ''">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 20h18M9 20V10m6 10V6m6 14V14" />
            </svg>
            <span class="ml-3" x-show="!sidebarCollapsed">Financeiro</span>
        </a>
        <div class="mt-2" x-data="{ open: false }">
            <button @click="open = !open" class="flex items-center w-full px-4 py-2 text-gray-700 hover:bg-gray-100" :title="sidebarCollapsed ? 'Administração' : ''">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V5a2 2 0 00-2-2H6a2 2 0 00-2 2v8m16 0v6a2 2 0 01-2 2H6a2 2 0 01-2-2v-6m16 0H4" /></svg>
                <span class="ml-3" x-show="!sidebarCollapsed">Administração</span>
                <svg x-show="!sidebarCollapsed" :class="{'rotate-90': open}" class="w-4 h-4 ml-auto transform transition-transform" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
            </button>
            <div x-show="open && !sidebarCollapsed" x-collapse class="mt-1 space-y-1 pl-12" x-cloak>
                <a href="{{ route('clinicas.index') }}" class="block py-1 hover:underline">Clínicas</a>
                <a href="{{ route('cadeiras.index') }}" class="block py-1 hover:underline">Cadeiras</a>
                <a href="{{ route('formularios.index') }}" class="block py-1 hover:underline">Formulários</a>
            </div>
        </div>
        <div class="mt-2" x-data="{ openAccess: false }">
            <button @click="openAccess = !openAccess" class="flex items-center w-full px-4 py-2 text-gray-700 hover:bg-gray-100" :title="sidebarCollapsed ? 'Gestão de Acessos' : ''">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 11V7a6 6 0 1112 0v4" />
                <rect width="14" height="10" x="5" y="11" rx="2" ry="2" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
            </svg>
                <span class="ml-3" x-show="!sidebarCollapsed">Gestão de Acessos</span>
                <svg x-show="!sidebarCollapsed" :class="{'rotate-90': openAccess}" class="w-4 h-4 ml-auto transform transition-transform" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
            </button>
            <div x-show="openAccess && !sidebarCollapsed" x-collapse class="mt-1 space-y-1 pl-12" x-cloak>
                <a href="{{ route('usuarios.index') }}" class="block py-1 hover:underline">Usuários</a>
                <a href="{{ route('perfis.index') }}" class="block py-1 hover:underline">Perfis</a>
            </div>
        </div>
        @endif
    </nav>
</div>
