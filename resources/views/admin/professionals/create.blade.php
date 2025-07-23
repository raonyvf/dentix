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
    <form method="POST" action="{{ route('profissionais.store') }}" enctype="multipart/form-data" class="space-y-6" x-data="professionalForm()">
        @csrf
        <div class="mb-4 border-b flex gap-4">
            <button type="button" @click="tab='dados'" :class="tab==='dados' ? 'border-b-2 border-blue-600' : ''" class="pb-2">Dados cadastrais</button>
            <button type="button" @click="tab='profissionais'" :class="tab==='profissionais' ? 'border-b-2 border-blue-600' : ''" class="pb-2">Dados admissionais</button>
            <button type="button" @click="tab='clinicas'" :class="tab==='clinicas' ? 'border-b-2 border-blue-600' : ''" class="pb-2">Remuneração</button>
        </div>
        <div x-show="tab==='dados'" class="space-y-6">
        <div class="rounded-sm border border-stroke bg-gray-50 p-4">
            <button type="button" @click="dadosAccordion = !dadosAccordion" class="flex items-center w-full">
                <h2 class="text-sm font-medium text-gray-700">Dados pessoais</h2>
                <svg :class="{'rotate-90': dadosAccordion}" class="w-4 h-4 ml-auto transform transition-transform" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
            </button>
            <div x-show="dadosAccordion" x-collapse class="mt-4 space-y-6">
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
                    <label class="mb-2 block text-sm font-medium text-gray-700">Data de nascimento</label>
                    <input class="w-full rounded border-[1.5px] border-stroke bg-gray-2 py-3 px-5 text-sm text-black focus:border-primary focus:outline-none" type="date" name="data_nascimento" value="{{ old('data_nascimento') }}" />
                </div>
                <div>
                    <label class="mb-2 block text-sm font-medium text-gray-700">Sexo</label>
                    <select name="sexo" class="w-full rounded border-[1.5px] border-stroke bg-gray-2 py-3 px-5 text-sm text-black focus:border-primary focus:outline-none">
                        <option value="">Selecionar sexo</option>
                        <option value="M" @selected(old('sexo') == 'M')>Masculino</option>
                        <option value="F" @selected(old('sexo') == 'F')>Feminino</option>
                    </select>
                </div>
                <div>
                    <label class="mb-2 block text-sm font-medium text-gray-700">Naturalidade</label>
                    <input class="w-full rounded border-[1.5px] border-stroke bg-gray-2 py-3 px-5 text-sm text-black focus:border-primary focus:outline-none" type="text" name="naturalidade" value="{{ old('naturalidade') }}" />
                </div>
                <div>
                    <label class="mb-2 block text-sm font-medium text-gray-700">Nacionalidade</label>
                    <input class="w-full rounded border-[1.5px] border-stroke bg-gray-2 py-3 px-5 text-sm text-black focus:border-primary focus:outline-none" type="text" name="nacionalidade" value="{{ old('nacionalidade') }}" />
                </div>
            </div>
            <div class="rounded-sm border border-stroke bg-gray-50 p-4 mt-4">
                <h3 class="mb-4 text-sm font-medium text-gray-700">Foto</h3>
                <input type="file" name="photo" class="w-full text-sm" />
            </div>
            </div>
        </div>
        <div class="rounded-sm border border-stroke bg-gray-50 p-4">
            <button type="button" @click="documentosAccordion = !documentosAccordion" class="flex items-center w-full">
                <h2 class="text-sm font-medium text-gray-700">Documentos</h2>
                <svg :class="{'rotate-90': documentosAccordion}" class="w-4 h-4 ml-auto transform transition-transform" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
            </button>
            <div x-show="documentosAccordion" x-collapse class="mt-4 space-y-6">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="mb-2 block text-sm font-medium text-gray-700">CPF</label>
                        <input class="w-full rounded border-[1.5px] border-stroke bg-gray-2 py-3 px-5 text-sm text-black focus:border-primary focus:outline-none" type="text" name="cpf" value="{{ old('cpf') }}" />
                    </div>
                    <div>
                        <label class="mb-2 block text-sm font-medium text-gray-700">RG</label>
                        <input class="w-full rounded border-[1.5px] border-stroke bg-gray-2 py-3 px-5 text-sm text-black focus:border-primary focus:outline-none" type="text" name="rg" value="{{ old('rg') }}" />
                    </div>
                </div>
            </div>
        </div>
        <div class="rounded-sm border border-stroke bg-gray-50 p-4">
            <button type="button" @click="contatoAccordion = !contatoAccordion" class="flex items-center w-full">
                <h2 class="text-sm font-medium text-gray-700">Dados de contato</h2>
                <svg :class="{'rotate-90': contatoAccordion}" class="w-4 h-4 ml-auto transform transition-transform" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
            </button>
            <div x-show="contatoAccordion" x-collapse class="mt-4 space-y-6">
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
        </div>
        <div class="rounded-sm border border-stroke bg-gray-50 p-4">
            <button type="button" @click="enderecoAccordion = !enderecoAccordion" class="flex items-center w-full">
                <h2 class="text-sm font-medium text-gray-700">Endereço</h2>
                <svg :class="{'rotate-90': enderecoAccordion}" class="w-4 h-4 ml-auto transform transition-transform" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
            </button>
            <div x-show="enderecoAccordion" x-collapse class="mt-4 space-y-6">
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
        </div>
        <div x-show="tab==='profissionais'" class="space-y-6">
            <div class="rounded-sm border border-stroke bg-gray-50 p-4">
                <button type="button" @click="atribuicoesAccordion = !atribuicoesAccordion" class="flex items-center w-full">
                    <h2 class="text-sm font-medium text-gray-700">Atribuições</h2>
                    <svg :class="{'rotate-90': atribuicoesAccordion}" class="w-4 h-4 ml-auto transform transition-transform" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
                </button>
                <div x-show="atribuicoesAccordion" x-collapse class="mt-4 space-y-4">
                    <textarea name="atribuicoes" class="w-full rounded border-[1.5px] border-stroke bg-gray-2 py-2 px-3 text-sm"></textarea>
                </div>
            </div>
            <div class="rounded-sm border border-stroke bg-gray-50 p-4">
                <button type="button" @click="dadosFuncionaisAccordion = !dadosFuncionaisAccordion" class="flex items-center w-full">
                    <h2 class="text-sm font-medium text-gray-700">Dados funcionais</h2>
                    <svg :class="{'rotate-90': dadosFuncionaisAccordion}" class="w-4 h-4 ml-auto transform transition-transform" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
                </button>
                <div x-show="dadosFuncionaisAccordion" x-collapse class="mt-4 space-y-6">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div class="sm:col-span-2">
                            <label class="mb-2 block text-sm font-medium text-gray-700">Cargo</label>
                            <input class="w-full rounded border-[1.5px] border-stroke bg-gray-2 py-3 px-5 text-sm text-black focus:border-primary focus:outline-none" type="text" name="cargo" value="{{ old('cargo') }}" />
                        </div>
                        <div class="sm:col-span-2" x-data="{ dentista: {{ old('dentista') ? 'true' : 'false' }} }">
                            <label class="inline-flex items-center gap-2 mb-2 text-sm font-medium text-gray-700">
                                <input type="checkbox" name="dentista" x-model="dentista" value="1" class="rounded" @checked(old('dentista')) /> Dentista
                            </label>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4" x-show="dentista">
                                <div>
                                    <label class="mb-2 block text-sm font-medium text-gray-700">CRO</label>
                                    <input x-bind:required="dentista" x-bind:disabled="!dentista" class="w-full rounded border-[1.5px] border-stroke bg-gray-2 py-3 px-5 text-sm text-black focus:border-primary focus:outline-none" type="text" name="cro" placeholder="CRO" value="{{ old('cro') }}" />
                                </div>
                                <div>
                                    <label class="mb-2 block text-sm font-medium text-gray-700">Especialidade</label>
                                    <input class="w-full rounded border-[1.5px] border-stroke bg-gray-2 py-3 px-5 text-sm text-black focus:border-primary focus:outline-none" type="text" name="especialidade" placeholder="Especialidade" value="{{ old('especialidade') }}" />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="rounded-sm border border-stroke bg-gray-50 p-4">
                <button type="button" @click="horarioAccordion = !horarioAccordion" class="flex items-center w-full">
                    <h2 class="text-sm font-medium text-gray-700">Horário de trabalho</h2>
                    <svg :class="{'rotate-90': horarioAccordion}" class="w-4 h-4 ml-auto transform transition-transform" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
                </button>
                <div x-show="horarioAccordion" x-collapse class="mt-4 space-y-4">
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
                        <div x-show="horarioClinic == '{{ $clinic->id }}'" class="space-y-2" x-ref="clinic{{ $clinic->id }}">
                            @foreach ($diasSemana as $diaKey => $diaLabel)
                                <div class="flex items-center gap-2">
                                    <input type="checkbox" name="horarios[{{ $clinic->id }}][{{ $diaKey }}][ativo]" value="1" class="rounded">
                                    <span class="w-28 text-sm">{{ $diaLabel }}</span>
                                    <input type="time" name="horarios[{{ $clinic->id }}][{{ $diaKey }}][hora_inicio]" class="border rounded px-2 py-1 text-sm">
                                    <input type="time" name="horarios[{{ $clinic->id }}][{{ $diaKey }}][hora_fim]" class="border rounded px-2 py-1 text-sm">
                                </div>
                            @endforeach
                            <button type="button" class="mt-2 px-3 py-1 bg-blue-600 text-white text-sm rounded" @click="aplicarHorarios({{ $clinic->id }})">Aplicar para os selecionados</button>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
        <div x-show="tab==='clinicas'" class="space-y-4">
            <div>
                <label class="text-sm block mb-1">Salário base</label>
                <input type="text" name="salario_base" class="w-full rounded border-[1.5px] border-stroke bg-gray-2 py-2 px-3 text-sm currency-brl" value="{{ old('salario_base') }}">
            </div>
            @foreach ($clinics as $clinic)
                <div class="border rounded p-4 space-y-2">
                    <div class="flex items-center gap-2">
                        <input type="checkbox" name="clinicas[{{ $clinic->id }}][selected]" value="1" class="rounded">
                        <span class="font-medium text-sm">{{ $clinic->nome }}</span>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="text-sm block mb-1">Comissao (%)</label>
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

        <div class="flex justify-end pt-4">
            <button type="submit" class="py-2 px-4 bg-blue-600 text-white rounded hover:bg-blue-700">Salvar</button>
        </div>
    </form>
</div>


@endsection

@push('scripts')
<script>
    function professionalForm() {
        return {
            tab: 'dados',
            horarioClinic: '',
            dadosAccordion: false,
            documentosAccordion: false,
            contatoAccordion: false,
            enderecoAccordion: false,
            atribuicoesAccordion: false,
            dadosFuncionaisAccordion: false,
            horarioAccordion: false,
            aplicarHorarios(clinicId) {
                const dias = ['segunda','terca','quarta','quinta','sexta','sabado','domingo'];
                const container = this.$refs['clinic' + clinicId];
                if (!container) return;
                const inicioBase = container.querySelector(`input[name="horarios[${clinicId}][segunda][hora_inicio]"]`).value;
                const fimBase = container.querySelector(`input[name="horarios[${clinicId}][segunda][hora_fim]"]`).value;
                dias.slice(1).forEach(dia => {
                    const cb = container.querySelector(`input[name="horarios[${clinicId}][${dia}][ativo]"]`);
                    if (cb && cb.checked) {
                        const inicio = container.querySelector(`input[name="horarios[${clinicId}][${dia}][hora_inicio]"]`);
                        const fim = container.querySelector(`input[name="horarios[${clinicId}][${dia}][hora_fim]"]`);
                        if (inicio) inicio.value = inicioBase;
                        if (fim) fim.value = fimBase;
                    }
                });
            }
        }
    }
</script>
@endpush
