<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" x-data="{ sidebarCollapsed: JSON.parse(localStorage.getItem('sidebarCollapsed') ?? 'false') }" x-init="$watch('sidebarCollapsed', val => localStorage.setItem('sidebarCollapsed', val))" class="h-full">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>{{ config('app.name') }}</title>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
        <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
        <script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/pt.js"></script>
        <script>
            document.addEventListener('alpine:init', () => {

        });
        document.addEventListener('DOMContentLoaded', () => {

        });
        </script>
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    </head>
    <body class="flex h-screen bg-gray-100">
    @auth
        @unless(isset($hideNav) && $hideNav)
            <aside :class="sidebarCollapsed ? 'w-20' : 'w-64'" class="h-screen bg-white border-r shadow transition-all duration-300 overflow-y-auto">
                @include('partials.sidebar')
            </aside>
        @endunless
    @endauth
    <div class="flex flex-col flex-1 h-screen min-h-0">
        @auth
            @unless(isset($hideNav) && $hideNav)
                @include('partials.topbar')
            @endunless
        @endauth
        <main class="flex-1 overflow-y-auto @auth p-6 @endauth">
            @if ($errors->any() && !(isset($hideErrors) && $hideErrors))
                @include('components.alert-error', ['slot' => $errors->first()])
            @endif
            @if (session('success'))
                @include('components.alert-success', ['slot' => session('success')])
            @endif
            @yield('content')
        </main>
    </div>
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        @stack('scripts')
    </body>
    </html>