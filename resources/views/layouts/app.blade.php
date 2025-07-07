<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name') }}</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 0; padding: 0; }
        header { background: #0d6efd; color: #fff; padding: 1rem; }
        nav a { color: #fff; margin-right: 1rem; }
        .container { padding: 1rem; }
        .grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1rem; }
        .card { border: 1px solid #ccc; padding: 1rem; border-radius: 5px; }
        .btn { display: inline-block; padding: 0.5rem 1rem; background: #0d6efd; color: #fff; text-decoration: none; border-radius: 3px; }
    </style>
</head>
<body>
<header>
    <nav>
        <a href="/">Home</a>
        <a href="/admin">Admin</a>
    </nav>
</header>
<div class="container">
    @yield('content')
</div>
</body>
</html>
