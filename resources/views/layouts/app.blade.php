<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" x-data="{ sidebarCollapsed: JSON.parse(localStorage.getItem('sidebarCollapsed') ?? 'false') }" x-init="$watch('sidebarCollapsed', val => localStorage.setItem('sidebarCollapsed', val))" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="flex h-full bg-gray-100">
    @auth
        @unless(isset($hideNav) && $hideNav)
            <aside :class="sidebarCollapsed ? 'w-20' : 'w-64'" class="h-full bg-white border-r shadow transition-all duration-300">
                @include('partials.sidebar')
            </aside>
        @endunless
    @endauth
    <div class="flex flex-col flex-1 min-h-screen">
        @auth
            @unless(isset($hideNav) && $hideNav)
                @include('partials.topbar')
            @endunless
        @endauth
        <main class="flex-1 p-6 overflow-y-auto">
            @if (session('success'))
                @include('components.alert-success', ['slot' => session('success')])
            @endif
            @yield('content')
        </main>
    </div>
</body>
</html>
