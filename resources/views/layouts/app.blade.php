<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'Dentix' }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="antialiased">
    @unless($hideNav ?? false)
        @include('partials.nav')
    @endunless

    @unless($hideErrors ?? false)
        @include('partials.topbar')
    @endunless

    @yield('content')
</body>
</html>
