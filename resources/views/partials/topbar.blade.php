<header class="flex items-center h-16 bg-white border-b shadow px-4" x-data="{ open: false }">
    <button @click="sidebarCollapsed = !sidebarCollapsed" class="p-2 text-gray-600 hover:text-gray-900">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
        </svg>
    </button>
    <div class="flex-1 flex justify-center">
        <div class="flex items-center gap-2 w-full max-w-md">
            <div class="relative flex-1">
                <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                    <svg class="w-5 h-5 text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35m0 0A7.5 7.5 0 103 10.5a7.5 7.5 0 0013.65 6.15z" />
                    </svg>
                </span>
                <input type="text" placeholder="Pesquisar" class="w-full pl-10 pr-4 py-2 border rounded-lg bg-gray-100 focus:border-primary focus:ring-0" />
            </div>
            @php
                $user = Auth::user();
                $clinics = $user->clinics;
                $currentClinic = session('clinic_id');
            @endphp
            @if ($clinics->count() > 1)
                <form method="POST" action="{{ route('clinicas.selecionar') }}">
                    @csrf
                    <select name="clinic_id" onchange="this.form.submit()" class="border-gray-300 rounded py-2 px-2 text-sm">
                        @foreach ($clinics as $clinic)
                            <option value="{{ $clinic->id }}" @selected($clinic->id == $currentClinic)>{{ $clinic->nome }}</option>
                        @endforeach
                    </select>
                </form>
            @elseif ($clinics->count() === 1)
                <span class="text-sm text-gray-600">{{ $clinics->first()->nome }}</span>
            @endif
        </div>
    </div>
    <button class="ml-4 p-2 text-gray-600 hover:text-gray-900">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6 6 0 10-12 0v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
        </svg>
    </button>
    <div class="relative ml-4" x-data="{ userOpen: false }">
        @php
            $user = Auth::user();
            $displayName = '';
            $initials = '';

            if ($user->person) {
                $displayName = trim($user->person->first_name . ' ' . $user->person->last_name);
                $initials = strtoupper(substr($user->person->first_name, 0, 1) . substr($user->person->last_name, 0, 1));
                $showEmail = false;
            } else {
                if ($user->isSuperAdmin()) {
                    $displayName = 'Super Administrador';
                } elseif ($user->isOrganizationAdmin()) {
                    $displayName = 'Usuário admin';
                } else {
                    $displayName = $user->email;
                }
                $initials = strtoupper(substr($displayName, 0, 1));
                $showEmail = true;
            }
        @endphp
        <button @click="userOpen = !userOpen" class="flex items-center focus:outline-none">
            <span class="w-8 h-8 bg-gray-200 rounded-full flex items-center justify-center text-gray-700 uppercase">{{ $initials }}</span>
            <svg class="w-4 h-4 ml-1 text-gray-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
            </svg>
        </button>
        <div x-show="userOpen" @click.away="userOpen = false" x-transition class="absolute right-0 mt-2 w-48 bg-white border rounded shadow-md" x-cloak>
            <div class="px-4 py-2 text-gray-700 border-b">
                <p>{{ $displayName }}</p>
                @if($showEmail)
                    <p class="text-xs text-gray-500">{{ $user->email }}</p>
                @endif
            </div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="w-full text-left px-4 py-2 hover:bg-gray-100">Sair</button>
            </form>
        </div>
    </div>
</header>
