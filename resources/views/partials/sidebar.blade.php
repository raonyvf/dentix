<div class="flex flex-col h-full" x-data="{ adminOpen: false }">
    <div class="h-16 flex items-center justify-center border-b">
        <span class="text-xl font-bold" x-show="!sidebarCollapsed">Dentix</span>
        <span x-show="sidebarCollapsed" class="text-xl font-bold">DX</span>
    </div>
    @php $isSuperAdmin = Auth::user() && Auth::user()->isSuperAdmin(); @endphp
    <nav class="flex-1 overflow-y-auto py-4">
        @if($isSuperAdmin)
        <a href="{{ route('backend.index') }}" class="flex items-center px-4 py-2 text-gray-700 hover:bg-gray-100" :title="sidebarCollapsed ? 'Backend' : ''">
            <x-lucide-icon name="database" class="w-6 h-6" />
            <span class="ml-3" x-show="!sidebarCollapsed">Backend</span>
        </a>
        <a href="{{ route('usuarios-admin.index') }}" class="flex items-center px-4 py-2 text-gray-700 hover:bg-gray-100" :title="sidebarCollapsed ? 'Usuários Admin' : ''">
            <x-lucide-icon name="user-cog" class="w-6 h-6" />
            <span class="ml-3" x-show="!sidebarCollapsed">Usuários Admin</span>
        </a>
        @else
        <a href="{{ route('admin.index') }}" class="flex items-center px-4 py-2 text-gray-700 hover:bg-gray-100" :title="sidebarCollapsed ? 'Dashboard' : ''">
            <x-lucide-icon name="home" class="w-6 h-6" />
            <span class="ml-3" x-show="!sidebarCollapsed">Dashboard</span>
        </a>
        <a href="{{ route('pacientes.index') }}" class="flex items-center px-4 py-2 text-gray-700 hover:bg-gray-100" :title="sidebarCollapsed ? 'Pacientes' : ''">
            <x-lucide-icon name="users" class="w-6 h-6" />
            <span class="ml-3" x-show="!sidebarCollapsed">Pacientes</span>
        </a>
        <a href="{{ route('agenda.index') }}" class="flex items-center px-4 py-2 text-gray-700 hover:bg-gray-100" :title="sidebarCollapsed ? 'Agenda' : ''">
            <x-lucide-icon name="calendar" class="w-6 h-6" />
            <span class="ml-3" x-show="!sidebarCollapsed">Agenda</span>
        </a>
        <a href="#" class="flex items-center px-4 py-2 text-gray-700 hover:bg-gray-100" :title="sidebarCollapsed ? 'Prontuários' : ''">
            <x-lucide-icon name="file-text" class="w-6 h-6" />
            <span class="ml-3" x-show="!sidebarCollapsed">Prontuários</span>
        </a>
        <a href="{{ route('profissionais.index') }}" class="flex items-center px-4 py-2 text-gray-700 hover:bg-gray-100" :title="sidebarCollapsed ? 'Profissionais' : ''">
            <x-lucide-icon name="stethoscope" class="w-6 h-6" />
            <span class="ml-3" x-show="!sidebarCollapsed">Profissionais</span>
        </a>
        <a href="{{ route('escalas.index') }}" class="flex items-center px-4 py-2 text-gray-700 hover:bg-gray-100" :title="sidebarCollapsed ? 'Escalas de Trabalho' : ''">
            <x-lucide-icon name="clipboard-list" class="w-6 h-6" />
            <span class="ml-3" x-show="!sidebarCollapsed">Escalas de Trabalho</span>
        </a>
        <a href="{{ route('estoque.index') }}" class="flex items-center px-4 py-2 text-gray-700 hover:bg-gray-100" :title="sidebarCollapsed ? 'Estoque' : ''">
            <x-lucide-icon name="package" class="w-6 h-6" />
            <span class="ml-3" x-show="!sidebarCollapsed">Estoque</span>
        </a>
        <a href="{{ route('financeiro.index') }}" class="flex items-center px-4 py-2 text-gray-700 hover:bg-gray-100" :title="sidebarCollapsed ? 'Financeiro' : ''">
            <x-lucide-icon name="wallet" class="w-6 h-6" />
            <span class="ml-3" x-show="!sidebarCollapsed">Financeiro</span>
        </a>
        <div class="mt-2" x-data="{ open: false }">
            <button @click="open = !open" class="flex items-center w-full px-4 py-2 text-gray-700 hover:bg-gray-100" :title="sidebarCollapsed ? 'Administração' : ''">
                <x-lucide-icon name="settings" class="w-6 h-6" />
                <span class="ml-3" x-show="!sidebarCollapsed">Administração</span>
                <x-lucide-icon name="chevron-right" x-show="!sidebarCollapsed" :class="{'rotate-90': open}" class="w-4 h-4 ml-auto transform transition-transform" />
            </button>
            <div x-show="open && !sidebarCollapsed" x-collapse class="mt-1 space-y-1 pl-12" x-cloak>
                <a href="{{ route('clinicas.index') }}" class="block py-1 hover:underline">Clínicas</a>
                <a href="{{ route('cadeiras.index') }}" class="block py-1 hover:underline">Cadeiras</a>
                <a href="{{ route('formularios.index') }}" class="block py-1 hover:underline">Formulários</a>
            </div>
        </div>
        <div class="mt-2" x-data="{ openAccess: false }">
            <button @click="openAccess = !openAccess" class="flex items-center w-full px-4 py-2 text-gray-700 hover:bg-gray-100" :title="sidebarCollapsed ? 'Gestão de Acessos' : ''">
            <x-lucide-icon name="shield-check" class="w-6 h-6" />
                <span class="ml-3" x-show="!sidebarCollapsed">Gestão de Acessos</span>
                <x-lucide-icon name="chevron-right" x-show="!sidebarCollapsed" :class="{'rotate-90': openAccess}" class="w-4 h-4 ml-auto transform transition-transform" />
            </button>
            <div x-show="openAccess && !sidebarCollapsed" x-collapse class="mt-1 space-y-1 pl-12" x-cloak>
                <a href="{{ route('usuarios.index') }}" class="block py-1 hover:underline">Usuários</a>
                <a href="{{ route('perfis.index') }}" class="block py-1 hover:underline">Perfis</a>
            </div>
        </div>
        @endif
    </nav>
</div>
