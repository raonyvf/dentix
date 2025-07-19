@extends('layouts.app')

@section('content')
@include('partials.breadcrumbs', ['crumbs' => [
    ['label' => 'Dashboard', 'url' => route('admin.index')],
    ['label' => 'Usuários', 'url' => route('usuarios.index')],
    ['label' => 'Criar']
]])
<div class="w-full bg-white p-6 rounded-lg shadow">
    <h1 class="text-xl font-semibold mb-4">Criar Usuário</h1>
    @if ($errors->any())
        <div class="mb-4">
            <ul class="list-disc list-inside text-sm text-red-600">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <form method="POST" action="{{ route('usuarios.store') }}" enctype="multipart/form-data" class="space-y-6">
        @csrf
        <div class="rounded-sm border border-stroke bg-gray-50 p-4">
            <h2 class="mb-4 text-sm font-medium text-gray-700">Dados do Usuário</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div class="sm:col-span-2">
                    <label class="mb-2 block text-sm font-medium text-gray-700">Nome</label>
                    <input class="w-full rounded border-[1.5px] border-stroke bg-gray-2 py-3 px-5 text-sm text-black focus:border-primary focus:outline-none" type="text" name="name" value="{{ old('name') }}" required />
                </div>
                <div class="sm:col-span-2">
                    <label class="mb-2 block text-sm font-medium text-gray-700">Email</label>
                    <input class="w-full rounded border-[1.5px] border-stroke bg-gray-2 py-3 px-5 text-sm text-black focus:border-primary focus:outline-none" type="email" name="email" value="{{ old('email') }}" required />
                </div>
                <div class="sm:col-span-2">
                    <label class="mb-2 block text-sm font-medium text-gray-700">Senha (opcional)</label>
                    <input class="w-full rounded border-[1.5px] border-stroke bg-gray-2 py-3 px-5 text-sm text-black focus:border-primary focus:outline-none" type="password" name="password" />
                </div>
                <div>
                    <label class="mb-2 block text-sm font-medium text-gray-700">Telefone</label>
                    <input class="w-full rounded border-[1.5px] border-stroke bg-gray-2 py-3 px-5 text-sm text-black focus:border-primary focus:outline-none" type="text" name="phone" value="{{ old('phone') }}" />
                </div>
                <div>
                    <label class="mb-2 block text-sm font-medium text-gray-700">CPF</label>
                    <input class="w-full rounded border-[1.5px] border-stroke bg-gray-2 py-3 px-5 text-sm text-black focus:border-primary focus:outline-none" type="text" name="cpf" value="{{ old('cpf') }}" />
                </div>
                <div class="sm:col-span-2" x-data="{ dentista: {{ old('dentista') ? 'true' : 'false' }} }">
                    <label class="inline-flex items-center gap-2 mb-2 text-sm font-medium text-gray-700">
                        <input type="checkbox" name="dentista" x-model="dentista" value="1" class="rounded" @checked(old('dentista')) /> Dentista
                    </label>
                    <input x-bind:required="dentista" x-show="dentista" x-cloak class="w-full rounded border-[1.5px] border-stroke bg-gray-2 py-3 px-5 text-sm text-black focus:border-primary focus:outline-none" type="text" name="cro" placeholder="CRO" value="{{ old('cro') }}" />
                </div>
            </div>
        </div>
        <div class="rounded-sm border border-stroke bg-gray-50 p-4">
            <h2 class="mb-4 text-sm font-medium text-gray-700">Endereço</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div class="sm:col-span-2">
                    <label class="mb-2 block text-sm font-medium text-gray-700">Logradouro</label>
                    <input class="w-full rounded border-[1.5px] border-stroke bg-gray-2 py-3 px-5 text-sm text-black focus:border-primary focus:outline-none" type="text" name="endereco" value="{{ old('endereco') }}" placeholder="Número e complemento" />
                </div>
                <div>
                    <label class="mb-2 block text-sm font-medium text-gray-700">Cidade</label>
                    <input class="w-full rounded border-[1.5px] border-stroke bg-gray-2 py-3 px-5 text-sm text-black focus:border-primary focus:outline-none" type="text" name="cidade" value="{{ old('cidade') }}" />
                </div>
                <div>
                    <label class="mb-2 block text-sm font-medium text-gray-700">Estado</label>
                    <input class="w-full rounded border-[1.5px] border-stroke bg-gray-2 py-3 px-5 text-sm text-black focus:border-primary focus:outline-none" type="text" name="estado" value="{{ old('estado') }}" />
                </div>
                <div>
                    <label class="mb-2 block text-sm font-medium text-gray-700">CEP</label>
                    <input class="w-full rounded border-[1.5px] border-stroke bg-gray-2 py-3 px-5 text-sm text-black focus:border-primary focus:outline-none" type="text" name="cep" value="{{ old('cep') }}" />
                </div>
            </div>
        </div>
        <div class="rounded-sm border border-stroke bg-gray-50 p-4">
            <h2 class="mb-4 text-sm font-medium text-gray-700">Foto</h2>
            <input type="file" name="photo" class="w-full text-sm" />
        </div>
        <div>
            <h2 class="mb-4 text-sm font-medium text-gray-700">Perfis</h2>
            <div id="profiles-container" class="space-y-4"></div>
            <button type="button" id="add-profile" class="py-2 px-4 bg-gray-200 rounded hover:bg-gray-300 text-sm">Adicionar perfil</button>
            <template id="profile-clinic-template">
                <div class="profile-clinic-row rounded-sm border border-stroke bg-gray-50 p-4 space-y-4">
                    <div class="flex justify-between items-center">
                        <h3 class="font-medium text-sm profile-number">Perfil __number__</h3>
                        <button type="button" class="remove-profile text-red-600 hover:text-red-800">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                    <div class="flex flex-col sm:flex-row gap-4">
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
            </template>
        </div>
        <div class="flex justify-end pt-4">
            <button type="submit" class="py-2 px-4 bg-blue-600 text-white rounded hover:bg-blue-700">Salvar</button>
        </div>
    </form>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const container = document.getElementById('profiles-container');
        const template = document.getElementById('profile-clinic-template').innerHTML;
        let index = 0;

        function updateTitles() {
            container.querySelectorAll('.profile-number').forEach((el, idx) => {
                el.textContent = 'Perfil ' + (idx + 1);
            });
        }

        function addRow(profile = '', clinic = '') {
            const html = template.replace(/__index__/g, index).replace(/__number__/g, container.children.length + 1);
            container.insertAdjacentHTML('beforeend', html);
            const row = container.lastElementChild;
            row.querySelector('.remove-profile').addEventListener('click', () => {
                row.remove();
                updateTitles();
            });
            if (profile) row.querySelector(`select[name="profiles[${index}][profile_id]"]`).value = profile;
            if (clinic) row.querySelector(`select[name="profiles[${index}][clinic_id]"]`).value = clinic;
            index++;
            updateTitles();
        }

        document.getElementById('add-profile').addEventListener('click', () => addRow());

        const oldProfiles = @json(old('profiles', []));
        if (oldProfiles.length) {
            oldProfiles.forEach(p => addRow(p.profile_id ?? '', p.clinic_id ?? ''));
        } else {
            addRow();
        }
    });
</script>
@endsection
