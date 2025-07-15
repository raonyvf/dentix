@extends('layouts.app')

@section('content')
<div class="w-full bg-white p-6 rounded-lg shadow">
    <h1 class="text-xl font-semibold mb-4">Criar Usuário</h1>
    <form method="POST" action="{{ route('usuarios.store') }}" enctype="multipart/form-data" class="space-y-4">
        @csrf
        <div>
            <label class="mb-2 block text-sm font-medium text-gray-700">Nome</label>
            <input class="w-full rounded border-[1.5px] border-stroke bg-gray-2 py-3 px-5 text-sm text-black focus:border-primary focus:outline-none" type="text" name="name" required />
        </div>
        <div>
            <label class="mb-2 block text-sm font-medium text-gray-700">Email</label>
            <input class="w-full rounded border-[1.5px] border-stroke bg-gray-2 py-3 px-5 text-sm text-black focus:border-primary focus:outline-none" type="email" name="email" required />
        </div>
        <div>
            <label class="mb-2 block text-sm font-medium text-gray-700">Telefone</label>
            <input class="w-full rounded border-[1.5px] border-stroke bg-gray-2 py-3 px-5 text-sm text-black focus:border-primary focus:outline-none" type="text" name="phone" />
        </div>
        <div>
            <label class="mb-2 block text-sm font-medium text-gray-700">Foto</label>
            <input type="file" name="photo" class="w-full text-sm" />
        </div>
        <div>
            <label class="mb-2 block text-sm font-medium text-gray-700">Perfil</label>
            <select name="profile_id" required class="w-full rounded border-[1.5px] border-stroke bg-gray-2 py-3 px-5 text-sm text-black focus:border-primary focus:outline-none">
                <option value="">Selecione</option>
                @foreach ($profiles as $profile)
                    <option value="{{ $profile->id }}">{{ $profile->nome }}</option>
                @endforeach
            </select>
        </div>
        <button type="submit" class="py-2 px-4 bg-blue-600 text-white rounded hover:bg-blue-700">Salvar</button>
    </form>
</div>
@endsection
