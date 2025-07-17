@extends('layouts.app')

@section('content')
@include('partials.breadcrumbs', ['crumbs' => [
    ['label' => 'Dashboard', 'url' => route('admin.index')],
    ['label' => 'Usuários', 'url' => route('usuarios.index')],
    ['label' => 'Editar']
]])
<div class="w-full bg-white p-6 rounded-lg shadow" x-data="{ dentista: {{ old('dentista', $usuario->dentista) ? 'true' : 'false' }} }">
    <h1 class="text-xl font-semibold mb-4">Editar Usuário</h1>
    @if ($errors->any())
        <div class="mb-4">
            <ul class="list-disc list-inside text-sm text-red-600">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <form method="POST" action="{{ route('usuarios.update', $usuario) }}" enctype="multipart/form-data" class="space-y-4">
        @csrf
        @method('PUT')
        <div>
            <label class="mb-2 block text-sm font-medium text-gray-700">Nome</label>
            <input class="w-full rounded border-[1.5px] border-stroke bg-gray-2 py-3 px-5 text-sm text-black focus:border-primary focus:outline-none" type="text" name="name" value="{{ old('name', $usuario->name) }}" required />
        </div>
        <div>
            <label class="mb-2 block text-sm font-medium text-gray-700">Email</label>
            <input class="w-full rounded border-[1.5px] border-stroke bg-gray-2 py-3 px-5 text-sm text-black focus:border-primary focus:outline-none" type="email" name="email" value="{{ old('email', $usuario->email) }}" required />
        </div>
        <div>
            <label class="mb-2 block text-sm font-medium text-gray-700">Telefone</label>
            <input class="w-full rounded border-[1.5px] border-stroke bg-gray-2 py-3 px-5 text-sm text-black focus:border-primary focus:outline-none" type="text" name="phone" value="{{ old('phone', $usuario->phone) }}" />
        </div>
        <div>
            <label class="mb-2 block text-sm font-medium text-gray-700">Logradouro</label>
            <input class="w-full rounded border-[1.5px] border-stroke bg-gray-2 py-3 px-5 text-sm text-black focus:border-primary focus:outline-none" type="text" name="endereco" value="{{ old('endereco', $usuario->endereco) }}" />
        </div>
        <div>
            <label class="mb-2 block text-sm font-medium text-gray-700">CEP</label>
            <input class="w-full rounded border-[1.5px] border-stroke bg-gray-2 py-3 px-5 text-sm text-black focus:border-primary focus:outline-none" type="text" name="cep" value="{{ old('cep', $usuario->cep) }}" />
        </div>
        <div>
            <label class="mb-2 block text-sm font-medium text-gray-700">Cidade</label>
            <input class="w-full rounded border-[1.5px] border-stroke bg-gray-2 py-3 px-5 text-sm text-black focus:border-primary focus:outline-none" type="text" name="cidade" value="{{ old('cidade', $usuario->cidade) }}" />
        </div>
        <div>
            <label class="mb-2 block text-sm font-medium text-gray-700">Estado</label>
            <input class="w-full rounded border-[1.5px] border-stroke bg-gray-2 py-3 px-5 text-sm text-black focus:border-primary focus:outline-none" type="text" name="estado" value="{{ old('estado', $usuario->estado) }}" />
        </div>
        <div>
            <label class="mb-2 block text-sm font-medium text-gray-700">CPF</label>
            <input class="w-full rounded border-[1.5px] border-stroke bg-gray-2 py-3 px-5 text-sm text-black focus:border-primary focus:outline-none" type="text" name="cpf" value="{{ old('cpf', $usuario->cpf) }}" />
        </div>
        <div>
            <label class="inline-flex items-center gap-2 mb-2 text-sm font-medium text-gray-700">
                <input type="checkbox" name="dentista" x-model="dentista" value="1" class="rounded" @checked(old('dentista', $usuario->dentista)) /> Dentista
            </label>
            <input x-bind:required="dentista" x-show="dentista" x-cloak class="w-full rounded border-[1.5px] border-stroke bg-gray-2 py-3 px-5 text-sm text-black focus:border-primary focus:outline-none" type="text" name="cro" placeholder="CRO" value="{{ old('cro', $usuario->cro) }}" />
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

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const container = document.getElementById('profiles-container');
            const template = document.getElementById('profile-clinic-template').innerHTML;
            let index = 0;

            function addRow(profile = '', clinic = '') {
                const html = template.replace(/__index__/g, index);
                container.insertAdjacentHTML('beforeend', html);
                const row = container.lastElementChild;
                row.querySelector('select[name="profiles['+index+'][profile_id]"]').value = profile;
                row.querySelector('select[name="profiles['+index+'][clinic_id]"]').value = clinic;
                index++;
            }

            document.getElementById('add-profile').addEventListener('click', () => addRow());

            @foreach ($usuario->clinics as $clinic)
                addRow('{{ $clinic->pivot->profile_id }}', '{{ $clinic->id }}');
            @endforeach
        });
    </script>
@endpush
@endsection
