<div class="flex flex-col h-full" x-data="{ adminOpen: false }">
    <div class="h-16 flex items-center justify-center border-b">
        <span class="text-xl font-bold" x-show="!sidebarCollapsed">Dentix</span>
        <span x-show="sidebarCollapsed" class="text-xl font-bold">DX</span>
    </div>
    @php $isSuperAdmin = Auth::user() && Auth::user()->isSuperAdmin(); @endphp
    <nav class="flex-1 overflow-y-auto py-4">
        @if($isSuperAdmin)
        <a href="{{ route('backend.index') }}" class="flex items-center px-4 py-2 text-gray-700 hover:bg-gray-100" :title="sidebarCollapsed ? 'Backend' : ''">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" /></svg>
            <span class="ml-3" x-show="!sidebarCollapsed">Backend</span>
        </a>
        @else
        <a href="{{ route('admin.index') }}" class="flex items-center px-4 py-2 text-gray-700 hover:bg-gray-100" :title="sidebarCollapsed ? 'Dashboard' : ''">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0h6" /></svg>
            <span class="ml-3" x-show="!sidebarCollapsed">Dashboard</span>
        </a>
        @if(Auth::user()->hasAnyModulePermission('Pacientes'))
        <a href="{{ route('pacientes.index') }}" class="flex items-center px-4 py-2 text-gray-700 hover:bg-gray-100" :title="sidebarCollapsed ? 'Pacientes' : ''">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A13.937 13.937 0 0112 15c2.33 0 4.5.533 6.879 1.532M15 11a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
            <span class="ml-3" x-show="!sidebarCollapsed">Pacientes</span>
        </a>
        @endif
        @if(Auth::user()->hasAnyModulePermission('Agenda'))
        <a href="#" class="flex items-center px-4 py-2 text-gray-700 hover:bg-gray-100" :title="sidebarCollapsed ? 'Agenda' : ''">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
            <span class="ml-3" x-show="!sidebarCollapsed">Agenda</span>
        </a>
        @endif
        @if(Auth::user()->hasAnyModulePermission('Prontuários'))
        <a href="#" class="flex items-center px-4 py-2 text-gray-700 hover:bg-gray-100" :title="sidebarCollapsed ? 'Prontuários' : ''">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h7m-7 4h7" /></svg>
            <span class="ml-3" x-show="!sidebarCollapsed">Prontuários</span>
        </a>
        @endif
        @if(Auth::user()->hasAnyModulePermission('Profissionais'))
        <a href="#" class="flex items-center px-4 py-2 text-gray-700 hover:bg-gray-100" :title="sidebarCollapsed ? 'Profissionais' : ''">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16" /></svg>
            <span class="ml-3" x-show="!sidebarCollapsed">Profissionais</span>
        </a>
        @endif
        @if(Auth::user()->hasAnyModulePermission('Estoque'))
        <a href="#" class="flex items-center px-4 py-2 text-gray-700 hover:bg-gray-100" :title="sidebarCollapsed ? 'Estoque' : ''">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V8a1 1 0 00-.553-.894l-7-3.5a1 1 0 00-.894 0l-7 3.5A1 1 0 004 8v5m16 0v5a1 1 0 01-.553.894l-7 3.5a1 1 0 01-.894 0l-7-3.5A1 1 0 014 18v-5m16 0L12 7.5M4 13l8-4.5" /></svg>
            <span class="ml-3" x-show="!sidebarCollapsed">Estoque</span>
        </a>
        @endif
        @if(Auth::user()->hasAnyModulePermission('Financeiro'))
        <a href="#" class="flex items-center px-4 py-2 text-gray-700 hover:bg-gray-100" :title="sidebarCollapsed ? 'Financeiro' : ''">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3-1.343-3-3s1.343-3 3-3 3 1.343 3 3-1.343 3-3 3zm0 5a5 5 0 00-5 5v3h10v-3a5 5 0 00-5-5z" /></svg>
            <span class="ml-3" x-show="!sidebarCollapsed">Financeiro</span>
        </a>
        @endif
        @if(Auth::user()->hasAnyModulePermission('Clínicas') || Auth::user()->hasAnyModulePermission('Cadeiras'))
        <div class="mt-2" x-data="{ open: false }">
            <button @click="open = !open" class="flex items-center w-full px-4 py-2 text-gray-700 hover:bg-gray-100" :title="sidebarCollapsed ? 'Administração' : ''">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V5a2 2 0 00-2-2H6a2 2 0 00-2 2v8m16 0v6a2 2 0 01-2 2H6a2 2 0 01-2-2v-6m16 0H4" /></svg>
                <span class="ml-3" x-show="!sidebarCollapsed">Administração</span>
                <svg x-show="!sidebarCollapsed" :class="{'rotate-90': open}" class="w-4 h-4 ml-auto transform transition-transform" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
            </button>
            <div x-show="open && !sidebarCollapsed" x-collapse class="mt-1 space-y-1 pl-12" x-cloak>
                @if(Auth::user()->hasAnyModulePermission('Clínicas'))
                <a href="{{ route('clinicas.index') }}" class="block py-1 hover:underline">Clínicas</a>
                @endif
                @if(Auth::user()->hasAnyModulePermission('Cadeiras'))
                <a href="{{ route('cadeiras.index') }}" class="block py-1 hover:underline">Cadeiras</a>
                @endif
            </div>
        </div>
        @endif

        @if(Auth::user()->hasAnyModulePermission('Usuários'))
        <div class="mt-2" x-data="{ openAccess: false }">
            <button @click="openAccess = !openAccess" class="flex items-center w-full px-4 py-2 text-gray-700 hover:bg-gray-100" :title="sidebarCollapsed ? 'Gestão de Acessos' : ''">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m2 0a2 2 0 100-4 2 2 0 00-2 2m-6 0a2 2 0 11-4 0 2 2 0 004 0zm0 6h6m2 0a2 2 0 100-4 2 2 0 00-2 2m-6 0a2 2 0 11-4 0 2 2 0 004 0z" /></svg>
                <span class="ml-3" x-show="!sidebarCollapsed">Gestão de Acessos</span>
                <svg x-show="!sidebarCollapsed" :class="{'rotate-90': openAccess}" class="w-4 h-4 ml-auto transform transition-transform" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
            </button>
            <div x-show="openAccess && !sidebarCollapsed" x-collapse class="mt-1 space-y-1 pl-12" x-cloak>
                @if(Auth::user()->hasAnyModulePermission('Usuários'))
                <a href="{{ route('usuarios.index') }}" class="block py-1 hover:underline">Usuários</a>
                @endif
                <a href="{{ route('perfis.index') }}" class="block py-1 hover:underline">Perfis</a>
            </div>
        </div>
        @endif
        @endif
    </nav>
</div>
