@extends('layouts.app')

@section('content')
@include('partials.breadcrumbs', ['crumbs' => [
    ['label' => 'Dashboard', 'url' => route('admin.index')],
    ['label' => 'Profissionais', 'url' => route('profissionais.index')],
    ['label' => 'Criar']
]])
<div class="w-full bg-white p-6 rounded-lg shadow">
    <h1 class="text-xl font-semibold mb-4">Criar Profissional</h1>
    @if ($errors->any())
        <div class="mb-4">
            <ul class="list-disc list-inside text-sm text-red-600">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <form method="POST" action="{{ route('profissionais.store') }}" enctype="multipart/form-data" class="space-y-6" x-data="{ tab: 'dados', horarioClinic: '' }">
        @csrf
        <div class="mb-4 border-b flex gap-4">
            <button type="button" @click="tab='dados'" :class="tab==='dados' ? 'border-b-2 border-blue-600' : ''" class="pb-2">Dados pessoais</button>
            <button type="button" @click="tab='profissionais'" :class="tab==='profissionais' ? 'border-b-2 border-blue-600' : ''" class="pb-2">Dados Profissionais</button>
            <button type="button" @click="tab='clinicas'" :class="tab==='clinicas' ? 'border-b-2 border-blue-600' : ''" class="pb-2">Clínicas</button>
            <button type="button" @click="tab='horarios'" :class="tab==='horarios' ? 'border-b-2 border-blue-600' : ''" class="pb-2">Horários</button>
        </div>
        <div x-show="tab==='dados'" class="space-y-6">
        <div class="rounded-sm border border-stroke bg-gray-50 p-4">
            <h2 class="mb-4 text-sm font-medium text-gray-700">Dados do Profissional</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="mb-2 block text-sm font-medium text-gray-700">Nome</label>
                    <input class="w-full rounded border-[1.5px] border-stroke bg-gray-2 py-3 px-5 text-sm text-black focus:border-primary focus:outline-none" type="text" name="first_name" value="{{ old('first_name') }}" required />
                </div>
                <div>
                    <label class="mb-2 block text-sm font-medium text-gray-700">Nome do meio</label>
                    <input class="w-full rounded border-[1.5px] border-stroke bg-gray-2 py-3 px-5 text-sm text-black focus:border-primary focus:outline-none" type="text" name="middle_name" value="{{ old('middle_name') }}" />
                </div>
                <div>
                    <label class="mb-2 block text-sm font-medium text-gray-700">Último nome</label>
                    <input class="w-full rounded border-[1.5px] border-stroke bg-gray-2 py-3 px-5 text-sm text-black focus:border-primary focus:outline-none" type="text" name="last_name" value="{{ old('last_name') }}" required />
                </div>
                <div>
                    <label class="mb-2 block text-sm font-medium text-gray-700">CPF</label>
                    <input class="w-full rounded border-[1.5px] border-stroke bg-gray-2 py-3 px-5 text-sm text-black focus:border-primary focus:outline-none" type="text" name="cpf" value="{{ old('cpf') }}" />
                </div>
            </div>
        </div>
        <div class="rounded-sm border border-stroke bg-gray-50 p-4">
            <h2 class="mb-4 text-sm font-medium text-gray-700">Contato</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="mb-2 block text-sm font-medium text-gray-700">Email</label>
                    <input class="w-full rounded border-[1.5px] border-stroke bg-gray-2 py-3 px-5 text-sm text-black focus:border-primary focus:outline-none" type="email" name="email" value="{{ old('email') }}" required />
                </div>
                <div>
                    <label class="mb-2 block text-sm font-medium text-gray-700">Telefone</label>
                    <input class="w-full rounded border-[1.5px] border-stroke bg-gray-2 py-3 px-5 text-sm text-black focus:border-primary focus:outline-none" type="text" name="phone" value="{{ old('phone') }}" />
                </div>
            </div>
        </div>
        <div class="rounded-sm border border-stroke bg-gray-50 p-4">
            <h2 class="mb-4 text-sm font-medium text-gray-700">Endereço</h2>
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                <div>
                    <label class="mb-2 block text-sm font-medium text-gray-700">CEP</label>
                    <input class="w-full rounded border-[1.5px] border-stroke bg-gray-2 py-3 px-5 text-sm text-black focus:border-primary focus:outline-none" type="text" name="cep" value="{{ old('cep') }}" />
                </div>
                <div class="sm:col-span-2">
                    <label class="mb-2 block text-sm font-medium text-gray-700">Logradouro</label>
                    <input class="w-full rounded border-[1.5px] border-stroke bg-gray-2 py-3 px-5 text-sm text-black focus:border-primary focus:outline-none" type="text" name="logradouro" value="{{ old('logradouro') }}" />
                </div>
                <div>
                    <label class="mb-2 block text-sm font-medium text-gray-700">Número</label>
                    <input class="w-full rounded border-[1.5px] border-stroke bg-gray-2 py-3 px-5 text-sm text-black focus:border-primary focus:outline-none" type="text" name="numero" value="{{ old('numero') }}" />
                </div>
                <div>
                    <label class="mb-2 block text-sm font-medium text-gray-700">Complemento</label>
                    <input class="w-full rounded border-[1.5px] border-stroke bg-gray-2 py-3 px-5 text-sm text-black focus:border-primary focus:outline-none" type="text" name="complemento" value="{{ old('complemento') }}" />
                </div>
                <div>
                    <label class="mb-2 block text-sm font-medium text-gray-700">Bairro</label>
                    <input class="w-full rounded border-[1.5px] border-stroke bg-gray-2 py-3 px-5 text-sm text-black focus:border-primary focus:outline-none" type="text" name="bairro" value="{{ old('bairro') }}" />
                </div>
                <div>
                    <label class="mb-2 block text-sm font-medium text-gray-700">Cidade</label>
                    <input class="w-full rounded border-[1.5px] border-stroke bg-gray-2 py-3 px-5 text-sm text-black focus:border-primary focus:outline-none" type="text" name="cidade" value="{{ old('cidade') }}" />
                </div>
                <div>
                    <label class="mb-2 block text-sm font-medium text-gray-700">Estado</label>
                    <input class="w-full rounded border-[1.5px] border-stroke bg-gray-2 py-3 px-5 text-sm text-black focus:border-primary focus:outline-none" type="text" name="estado" value="{{ old('estado') }}" />
                </div>
            </div>
        </div>
        <div class="rounded-sm border border-stroke bg-gray-50 p-4">
            <h2 class="mb-4 text-sm font-medium text-gray-700">Foto</h2>
            <input type="file" name="photo" class="w-full text-sm" />
        </div>
        <div class="rounded-sm border border-stroke bg-gray-50 p-4">
            <h2 class="mb-4 text-sm font-medium text-gray-700">Senha</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="mb-2 block text-sm font-medium text-gray-700">Senha (opcional)</label>
                    <input class="w-full rounded border-[1.5px] border-stroke bg-gray-2 py-3 px-5 text-sm text-black focus:border-primary focus:outline-none" type="password" name="password" />
                </div>
                <div>
                    <label class="mb-2 block text-sm font-medium text-gray-700">Confirmar Senha</label>
                    <input class="w-full rounded border-[1.5px] border-stroke bg-gray-2 py-3 px-5 text-sm text-black focus:border-primary focus:outline-none" type="password" name="password_confirmation" />
                </div>
            </div>
            <p class="text-xs text-gray-500 mt-1">Se deixado em branco, uma senha aleatória será criada e enviada por e-mail.</p>
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
        </div> <!-- end dados tab -->
        <div x-show="tab==='profissionais'" class="space-y-6" x-cloak>
            <div class="rounded-sm border border-stroke bg-gray-50 p-4">
                <h2 class="mb-4 text-sm font-medium text-gray-700">Dados Profissionais</h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="sm:col-span-2">
                        <label class="mb-2 block text-sm font-medium text-gray-700">Cargo</label>
                        <input class="w-full rounded border-[1.5px] border-stroke bg-gray-2 py-3 px-5 text-sm text-black focus:border-primary focus:outline-none" type="text" name="cargo" value="{{ old('cargo') }}" />
                    </div>
                    <div class="sm:col-span-2" x-data="{ dentista: {{ old('dentista') ? 'true' : 'false' }} }">
                        <label class="inline-flex items-center gap-2 mb-2 text-sm font-medium text-gray-700">
                            <input type="checkbox" name="dentista" x-model="dentista" value="1" class="rounded" @checked(old('dentista')) /> Dentista
                        </label>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4" x-show="dentista" x-cloak>
                            <input x-bind:required="dentista" class="w-full rounded border-[1.5px] border-stroke bg-gray-2 py-3 px-5 text-sm text-black focus:border-primary focus:outline-none" type="text" name="cro" placeholder="CRO" value="{{ old('cro') }}" />
                            <input class="w-full rounded border-[1.5px] border-stroke bg-gray-2 py-3 px-5 text-sm text-black focus:border-primary focus:outline-none" type="text" name="especialidade" placeholder="Especialidade" value="{{ old('especialidade') }}" />
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div x-show="tab==='clinicas'" class="space-y-4">
            @foreach ($clinics as $clinic)
                <div class="border rounded p-4 space-y-2">
                    <div class="flex items-center gap-2">
                        <input type="checkbox" name="clinicas[{{ $clinic->id }}][selected]" value="1" class="rounded">
                        <span class="font-medium text-sm">{{ $clinic->nome }}</span>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="text-sm block mb-1">Comissão (%)</label>
                            <input type="number" step="0.01" name="clinicas[{{ $clinic->id }}][comissao]" class="w-full rounded border-[1.5px] border-stroke bg-gray-2 py-2 px-3 text-sm">
                        </div>
                        <div>
                            <label class="text-sm block mb-1">Status</label>
                            <select name="clinicas[{{ $clinic->id }}][status]" class="w-full rounded border-[1.5px] border-stroke bg-gray-2 py-2 px-3 text-sm">
                                <option value="Ativo">Ativo</option>
                                <option value="Inativo">Inativo</option>
                            </select>
                        </div>
                    </div>
                </div>
            @endforeach
            <div class="space-y-2">
                <label class="inline-flex items-center text-sm">
                    <input type="checkbox" name="compartilhar_agenda" value="1" class="rounded mr-2"> Compartilhar agenda entre clínicas
                </label>
                <label class="inline-flex items-center text-sm">
                    <input type="checkbox" name="comissao_unica" value="1" class="rounded mr-2"> Usar a mesma comissão em todas as clínicas
                </label>
            </div>
        </div>

        <div x-show="tab==='horarios'" class="space-y-4" x-cloak>
            <div>
                <label class="mb-2 block text-sm font-medium text-gray-700">Clínica</label>
                <select x-model="horarioClinic" class="w-full rounded border-[1.5px] border-stroke bg-gray-2 py-2 px-3 text-sm">
                    <option value="">Selecione</option>
                    @foreach ($clinics as $clinic)
                        <option value="{{ $clinic->id }}">{{ $clinic->nome }}</option>
                    @endforeach
                </select>
            </div>
            @php
                $diasSemana = [
                    'segunda' => 'Segunda',
                    'terca' => 'Terça',
                    'quarta' => 'Quarta',
                    'quinta' => 'Quinta',
                    'sexta' => 'Sexta',
                    'sabado' => 'Sábado',
                    'domingo' => 'Domingo',
                ];
            @endphp
            @foreach ($clinics as $clinic)
                <div x-show="horarioClinic == '{{ $clinic->id }}'" x-cloak class="space-y-2">
                    @foreach ($diasSemana as $diaKey => $diaLabel)
                        <div class="flex items-center gap-2">
                            <input type="checkbox" name="horarios[{{ $clinic->id }}][{{ $diaKey }}][ativo]" class="rounded">
                            <span class="w-28 text-sm">{{ $diaLabel }}</span>
                            <input type="time" name="horarios[{{ $clinic->id }}][{{ $diaKey }}][hora_inicio]" class="border rounded px-2 py-1 text-sm">
                            <input type="time" name="horarios[{{ $clinic->id }}][{{ $diaKey }}][hora_fim]" class="border rounded px-2 py-1 text-sm">
                        </div>
                    @endforeach
                </div>
            @endforeach
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
