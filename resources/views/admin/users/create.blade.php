@extends('layouts.app')

@section('content')
@include('partials.breadcrumbs', ['crumbs' => [
    ['label' => 'Dashboard', 'url' => route('admin.index')],
    ['label' => 'Usuários', 'url' => route('usuarios.index')],
    ['label' => 'Criar']
]])
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

        <div id="profiles-container" class="space-y-4"></div>
        <button type="button" id="add-profile" class="py-2 px-4 bg-gray-200 rounded hover:bg-gray-300 text-sm">Adicionar perfil</button>

        <div id="profile-clinic-template" class="hidden">
            <div class="profile-clinic-row flex flex-col sm:flex-row gap-4">
                <div class="flex-1">
                    <label class="mb-2 block text-sm font-medium text-gray-700">Perfil</label>
                    <select name="profiles[__index__][profile_id]" class="w-full rounded border-[1.5px] border-stroke bg-gray-2 py-3 px-5 text-sm text-black focus:border-primary focus:outline-none">
                        <option value="">Selecione</option>
                        @foreach ($profiles as $profile)
                            <option value="{{ $profile->id }}">{{ $profile->nome }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="flex-1">
                    <label class="mb-2 block text-sm font-medium text-gray-700">Clínica</label>
                    <select name="profiles[__index__][clinic_id]" class="w-full rounded border-[1.5px] border-stroke bg-gray-2 py-3 px-5 text-sm text-black focus:border-primary focus:outline-none">
                        <option value="">Selecione</option>
                        @foreach ($clinics as $clinic)
                            <option value="{{ $clinic->id }}">{{ $clinic->nome }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        <button type="submit" class="py-2 px-4 bg-blue-600 text-white rounded hover:bg-blue-700">Salvar</button>
    </form>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const container = document.getElementById('profiles-container');
        const template = document.getElementById('profile-clinic-template').innerHTML;
        let index = 0;

        function addRow() {
            const html = template.replace(/__index__/g, index);
            container.insertAdjacentHTML('beforeend', html);
            index++;
        }

        document.getElementById('add-profile').addEventListener('click', addRow);

        addRow();
    });
</script>
@endsection