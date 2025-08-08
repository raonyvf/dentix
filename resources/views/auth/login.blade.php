@extends('layouts.app')

@section('content')
<div class="flex items-center justify-center min-h-full">
    <div class="w-full max-w-4xl bg-white shadow-lg rounded-xl overflow-hidden flex flex-col md:flex-row">
        <div class="hidden md:flex md:w-1/2 items-center justify-center bg-gradient-to-br from-primary to-indigo-600 p-10 text-white">
            <div>
                <h2 class="text-4xl font-bold">Dentix</h2>
                <p class="mt-4 text-lg text-gray-100">Gerencie sua clínica com facilidade</p>
            </div>
        </div>
        <div class="w-full md:w-1/2 p-8 sm:p-10">
            <h1 class="text-2xl font-semibold mb-6 text-gray-700">Acessar sua conta</h1>
            <form method="POST" action="{{ route('login') }}" class="space-y-5">
                @csrf
                <div>
                    <label for="email" class="mb-2 block text-sm font-medium text-gray-700">Email</label>
                    <input id="email" value="{{ old('email') }}" class="w-full rounded border-[1.5px] border-stroke bg-gray-2 py-3 px-5 text-sm text-black focus:border-primary focus:outline-none" type="email" name="email" required autofocus />
                </div>
                <div>
                    <label for="password" class="mb-2 block text-sm font-medium text-gray-700">Senha</label>
                    <input id="password" class="w-full rounded border-[1.5px] border-stroke bg-gray-2 py-3 px-5 text-sm text-black focus:border-primary focus:outline-none" type="password" name="password" required />
                </div>
                <button type="submit" class="w-full py-2 px-4 bg-primary text-white rounded-lg hover:bg-primary/90">Entrar</button>
            </form>
        </div>
    </div>
</div>
@endsection
