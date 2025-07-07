@extends('layouts.app')

@section('content')
<div class="max-w-md mx-auto mt-10 bg-white p-6 rounded-lg shadow">
    <h1 class="text-2xl font-semibold mb-4">Login</h1>
    <form method="POST" action="{{ route('login') }}" class="space-y-4">
        @csrf
        <div>
            <label class="block text-sm font-medium text-gray-700">Email</label>
            <input class="mt-1 w-full rounded-md border-gray-300" type="email" name="email" required autofocus />
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700">Password</label>
            <input class="mt-1 w-full rounded-md border-gray-300" type="password" name="password" required />
        </div>
        <button type="submit" class="w-full py-2 px-4 bg-blue-600 text-white rounded hover:bg-blue-700">Login</button>
    </form>
</div>
@endsection
