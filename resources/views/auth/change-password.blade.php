@extends('layouts.app')

@section('content')
<div class="flex items-center justify-center min-h-screen py-10">
    <div class="w-full max-w-md bg-white shadow-lg rounded-xl overflow-hidden p-8">
        <h1 class="text-xl font-semibold mb-4">Alterar Senha</h1>
        @if ($errors->any())
            <div class="mb-4">
                <ul class="list-disc list-inside text-sm text-red-600">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <form method="POST" action="{{ route('password.update') }}" class="space-y-4">
            @csrf
            <div>
                <label class="mb-2 block text-sm font-medium text-gray-700">Nova Senha</label>
                <input class="w-full rounded border-[1.5px] border-stroke bg-gray-2 py-3 px-5 text-sm text-black focus:border-primary focus:outline-none" type="password" name="password" required />
            </div>
            <div>
                <label class="mb-2 block text-sm font-medium text-gray-700">Confirmar Senha</label>
                <input class="w-full rounded border-[1.5px] border-stroke bg-gray-2 py-3 px-5 text-sm text-black focus:border-primary focus:outline-none" type="password" name="password_confirmation" required />
            </div>
            <button type="submit" class="w-full py-2 px-4 bg-primary text-white rounded-lg hover:bg-primary/90">Salvar</button>
        </form>
    </div>
</div>
@endsection
