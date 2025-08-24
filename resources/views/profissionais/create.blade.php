@extends('layouts.app', ['hideErrors' => true])

@section('content')
@include('partials.breadcrumbs', ['crumbs' => [
    ['label' => 'Dashboard', 'url' => route('admin.index')],
    ['label' => 'Profissionais', 'url' => route('profissionais.index')],
    ['label' => 'Novo']
]])
<div class="w-full bg-white p-6 rounded-lg shadow" x-data="{ activeTab: 'dados', selectedClinics: @js(old('clinics', [])) }">
    <h1 class="text-xl font-semibold mb-4">Novo Profissional</h1>
    <div class="border-b mb-6">
        <ul class="flex flex-wrap -mb-px" aria-label="Tabs">
            <li>
                <button type="button" class="inline-flex items-center gap-2 py-4 px-4 border-b-2" @click="activeTab = 'dados'" :class="activeTab === 'dados' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'">
                    <x-lucide-user class="w-5 h-5" />
                    Dados cadastrais
                </button>
            </li>
            <li>
                <button type="button" class="inline-flex items-center gap-2 py-4 px-4 border-b-2" @click="activeTab = 'adm'" :class="activeTab === 'adm' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'">
                    <x-lucide-lock class="w-5 h-5" />
                    Dados admissionais
                </button>
            </li>
            <li>
                <button type="button" class="inline-flex items-center gap-2 py-4 px-4 border-b-2" @click="activeTab = 'rem'" :class="activeTab === 'rem' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'">
                    <x-lucide-wallet class="w-5 h-5" />
                    Remuneração
                </button>
            </li>
        </ul>
    </div>
    @if ($errors->any())
        <x-alert-error>
            <div>Por favor, preencha todos os campos obrigatórios (*).</div>
            <ul class="list-disc list-inside mt-2">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </x-alert-error>
    @endif
    <form method="POST" action="{{ route('profissionais.store') }}" enctype="multipart/form-data" class="space-y-6" novalidate>
        @csrf
        <div x-show="activeTab === 'dados'" class="space-y-6">
        <x-accordion-section title="Dados pessoais" :open="true">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="text-sm font-medium text-gray-700 mb-2 block">Nome <span class="text-red-500">*</span></label>
                    <input type="text" name="primeiro_nome" value="{{ old('primeiro_nome') }}" class="w-full rounded border-[1.5px] border-stroke bg-gray-2 py-3 px-5 text-sm text-black focus:border-primary focus:outline-none" required />
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-700 mb-2 block">Nome do meio</label>
                    <input type="text" name="nome_meio" value="{{ old('nome_meio') }}" class="w-full rounded border-[1.5px] border-stroke bg-gray-2 py-3 px-5 text-sm text-black focus:border-primary focus:outline-none" />
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-700 mb-2 block">Último nome <span class="text-red-500">*</span></label>
                    <input type="text" name="ultimo_nome" value="{{ old('ultimo_nome') }}" class="w-full rounded border-[1.5px] border-stroke bg-gray-2 py-3 px-5 text-sm text-black focus:border-primary focus:outline-none" required />
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-700 mb-2 block">Data de nascimento <span class="text-red-500">*</span></label>
                    <input type="text" name="data_nascimento" value="{{ old('data_nascimento') }}" class="datepicker w-full rounded border-[1.5px] border-stroke bg-gray-2 py-3 px-5 text-sm text-black focus:border-primary focus:outline-none" required readonly />
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-700 mb-2 block">Sexo <span class="text-red-500">*</span></label>
                    <select name="sexo" class="w-full rounded border-[1.5px] border-stroke bg-gray-2 py-3 px-5 text-sm text-black focus:border-primary focus:outline-none" required>
                        <option value="">Selecione</option>
                        <option value="Masculino" @selected(old('sexo')==='Masculino')>Masculino</option>
                        <option value="Feminino" @selected(old('sexo')==='Feminino')>Feminino</option>
                        <option value="Outro" @selected(old('sexo')==='Outro')>Outro</option>
                    </select>
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-700 mb-2 block">Naturalidade <span class="text-red-500">*</span></label>
                    <input type="text" name="naturalidade" value="{{ old('naturalidade') }}" class="w-full rounded border-[1.5px] border-stroke bg-gray-2 py-3 px-5 text-sm text-black focus:border-primary focus:outline-none" required />
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-700 mb-2 block">Nacionalidade <span class="text-red-500">*</span></label>
                    <input type="text" name="nacionalidade" value="{{ old('nacionalidade') }}" class="w-full rounded border-[1.5px] border-stroke bg-gray-2 py-3 px-5 text-sm text-black focus:border-primary focus:outline-none" required />
                </div>
                <div class="sm:col-span-2">
                    <label class="text-sm font-medium text-gray-700 mb-2 block">Foto</label>
                    <input type="file" name="foto" class="w-full text-sm text-gray-700" />
                </div>
            </div>
        </x-accordion-section>
        <x-accordion-section title="Documentos">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="text-sm font-medium text-gray-700 mb-2 block">CPF <span class="text-red-500">*</span></label>
                    <input type="text" name="cpf" value="{{ old('cpf') }}" class="w-full rounded border-[1.5px] border-stroke bg-gray-2 py-3 px-5 text-sm text-black focus:border-primary focus:outline-none" required />
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-700 mb-2 block">RG</label>
                    <input type="text" name="rg" value="{{ old('rg') }}" class="w-full rounded border-[1.5px] border-stroke bg-gray-2 py-3 px-5 text-sm text-black focus:border-primary focus:outline-none" />
                </div>
            </div>
        </x-accordion-section>
        <x-accordion-section title="Dados de contato">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="text-sm font-medium text-gray-700 mb-2 block">Email <span class="text-red-500">*</span></label>
                    <input type="email" name="email" value="{{ old('email') }}" class="w-full rounded border-[1.5px] border-stroke bg-gray-2 py-3 px-5 text-sm text-black focus:border-primary focus:outline-none" required />
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-700 mb-2 block">Telefone <span class="text-red-500">*</span></label>
                    <input type="text" name="telefone" value="{{ old('telefone') }}" class="w-full rounded border-[1.5px] border-stroke bg-gray-2 py-3 px-5 text-sm text-black focus:border-primary focus:outline-none" required />
                </div>
            </div>
        </x-accordion-section>
        <x-accordion-section title="Endereço">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="text-sm font-medium text-gray-700 mb-2 block">CEP <span class="text-red-500">*</span></label>
                    <input type="text" name="cep" value="{{ old('cep') }}" class="w-full rounded border-[1.5px] border-stroke bg-gray-2 py-3 px-5 text-sm text-black focus:border-primary focus:outline-none" required />
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-700 mb-2 block">Logradouro <span class="text-red-500">*</span></label>
                    <input type="text" name="logradouro" value="{{ old('logradouro') }}" class="w-full rounded border-[1.5px] border-stroke bg-gray-2 py-3 px-5 text-sm text-black focus:border-primary focus:outline-none" required />
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-700 mb-2 block">Número <span class="text-red-500">*</span></label>
                    <input type="text" name="numero" value="{{ old('numero') }}" class="w-full rounded border-[1.5px] border-stroke bg-gray-2 py-3 px-5 text-sm text-black focus:border-primary focus:outline-none" required />
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-700 mb-2 block">Complemento</label>
                    <input type="text" name="complemento" value="{{ old('complemento') }}" class="w-full rounded border-[1.5px] border-stroke bg-gray-2 py-3 px-5 text-sm text-black focus:border-primary focus:outline-none" />
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-700 mb-2 block">Bairro <span class="text-red-500">*</span></label>
                    <input type="text" name="bairro" value="{{ old('bairro') }}" class="w-full rounded border-[1.5px] border-stroke bg-gray-2 py-3 px-5 text-sm text-black focus:border-primary focus:outline-none" required />
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-700 mb-2 block">Cidade <span class="text-red-500">*</span></label>
                    <input type="text" name="cidade" value="{{ old('cidade') }}" class="w-full rounded border-[1.5px] border-stroke bg-gray-2 py-3 px-5 text-sm text-black focus:border-primary focus:outline-none" required />
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-700 mb-2 block">Estado <span class="text-red-500">*</span></label>
                    <input type="text" name="estado" value="{{ old('estado') }}" class="w-full rounded border-[1.5px] border-stroke bg-gray-2 py-3 px-5 text-sm text-black focus:border-primary focus:outline-none" required />
                </div>
            </div>
        </x-accordion-section>
    </div>
    <div x-show="activeTab === 'adm'" x-cloak x-data="{ funcao: '{{ old('funcao') }}', tipo_contrato: '{{ old('tipo_contrato') }}' }" class="space-y-6">
        <x-accordion-section title="Dados funcionais" :open="true">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="text-sm font-medium text-gray-700 mb-2 block">Número do funcionário</label>
                    <input type="text" name="numero_funcionario" value="{{ old('numero_funcionario') }}" class="w-full rounded border-[1.5px] border-stroke bg-gray-2 py-3 px-5 text-sm text-black focus:border-primary focus:outline-none" />
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-700 mb-2 block">E-mail corporativo</label>
                    <input type="text" name="email_corporativo" value="{{ old('email_corporativo') }}" class="w-full rounded border-[1.5px] border-stroke bg-gray-2 py-3 px-5 text-sm text-black focus:border-primary focus:outline-none" />
                </div>
            </div>
        </x-accordion-section>
        <x-accordion-section title="Contrato de Trabalho">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="text-sm font-medium text-gray-700 mb-2 block">Tipo de Contrato <span class="text-red-500">*</span></label>
                    <select name="tipo_contrato" x-model="tipo_contrato" class="w-full rounded border-[1.5px] border-stroke bg-gray-2 py-3 px-5 text-sm text-black focus:border-primary focus:outline-none" required>
                        <option value="">Selecione</option>
                        <option value="CLT" @selected(old('tipo_contrato')==='CLT')>CLT (Contrato com carteira assinada)</option>
                        <option value="PJ" @selected(old('tipo_contrato')==='PJ')>PJ (Pessoa Jurídica)</option>
                        <option value="Autônomo" @selected(old('tipo_contrato')==='Autônomo')>Autônomo</option>
                        <option value="Estágio" @selected(old('tipo_contrato')==='Estágio')>Estágio</option>
                        <option value="Temporário" @selected(old('tipo_contrato')==='Temporário')>Temporário</option>
                        <option value="Prestador de serviço" @selected(old('tipo_contrato')==='Prestador de serviço')>Prestador de serviço</option>
                        <option value="Outro" @selected(old('tipo_contrato')==='Outro')>Outro</option>
                    </select>
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-700 mb-2 block">Data de início do contrato <span class="text-red-500">*</span></label>
                    <input type="text" name="data_inicio_contrato" value="{{ old('data_inicio_contrato') }}" class="datepicker w-full rounded border-[1.5px] border-stroke bg-gray-2 py-3 px-5 text-sm text-black focus:border-primary focus:outline-none" required />
                </div>
                <div x-show="tipo_contrato && tipo_contrato !== 'CLT'" x-cloak>
                    <label class="text-sm font-medium text-gray-700 mb-2 block">Data de término do contrato</label>
                    <input type="text" name="data_fim_contrato" value="{{ old('data_fim_contrato') }}" class="datepicker w-full rounded border-[1.5px] border-stroke bg-gray-2 py-3 px-5 text-sm text-black focus:border-primary focus:outline-none" />
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-700 mb-2 block">Total de horas semanais</label>
                    <input type="number" name="total_horas_semanais" value="{{ old('total_horas_semanais') }}" class="w-full rounded border-[1.5px] border-stroke bg-gray-2 py-3 px-5 text-sm text-black focus:border-primary focus:outline-none" />
                </div>
                <div class="sm:col-span-2">
                    <label class="text-sm font-medium text-gray-700 mb-2 block">Clínicas <span class="text-red-500">*</span></label>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                        @foreach($clinics as $clinic)
                            <label class="flex items-center space-x-2">
                                <input type="checkbox" name="clinics[]" x-model="selectedClinics" value="{{ $clinic->id }}" @checked(in_array($clinic->id, old('clinics', []))) class="rounded border-stroke" :required="selectedClinics.length === 0" />
                                <span>{{ $clinic->nome }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-700 mb-2 block">Regime de trabalho <span class="text-red-500">*</span></label>
                    <select name="regime_trabalho" class="w-full rounded border-[1.5px] border-stroke bg-gray-2 py-3 px-5 text-sm text-black focus:border-primary focus:outline-none" required>
                        <option value="">Selecione</option>
                        <option value="Presencial" @selected(old('regime_trabalho')==='Presencial')>Presencial</option>
                        <option value="Remoto" @selected(old('regime_trabalho')=='Remoto')>Remoto</option>
                        <option value="Híbrido" @selected(old('regime_trabalho')==='Híbrido')>Híbrido</option>
                    </select>
                </div>
            </div>
        </x-accordion-section>
        <x-accordion-section title="Atribuição">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="text-sm font-medium text-gray-700 mb-2 block">Função <span class="text-red-500">*</span></label>
                    <select name="funcao" x-model="funcao" class="w-full rounded border-[1.5px] border-stroke bg-gray-2 py-3 px-5 text-sm text-black focus:border-primary focus:outline-none" required>
                        <option value="">Selecione</option>
                        <option value="Dentista" @selected(old('funcao')==='Dentista')>Dentista</option>
                        <option value="Assistencial" @selected(old('funcao')==='Assistencial')>Assistencial</option>
                        <option value="Administrativa" @selected(old('funcao')==='Administrativa')>Administrativa</option>
                        <option value="Recepcionista" @selected(old('funcao')==='Recepcionista')>Recepcionista</option>
                        <option value="Comercial" @selected(old('funcao')==='Comercial')>Comercial</option>
                        <option value="Outros" @selected(old('funcao')==='Outros')>Outros</option>
                    </select>
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-700 mb-2 block">Cargo <span class="text-red-500">*</span></label>
                    <input type="text" name="cargo" value="{{ old('cargo') }}" class="w-full rounded border-[1.5px] border-stroke bg-gray-2 py-3 px-5 text-sm text-black focus:border-primary focus:outline-none" required />
                </div>
            </div>
        </x-accordion-section>
        @php
            $registrosTitle = 'Registros';
        @endphp
        <x-accordion-section :title-html="$registrosTitle" x-show="funcao === 'Dentista'" x-cloak>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="text-sm font-medium text-gray-700 mb-2 block">CRO <span x-show="funcao === 'Dentista'" class="text-red-500">*</span></label>
                    <input type="number" name="cro" value="{{ old('cro') }}" x-bind:required="funcao === 'Dentista'" class="w-full rounded border-[1.5px] border-stroke bg-gray-2 py-3 px-5 text-sm text-black focus:border-primary focus:outline-none" />
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-700 mb-2 block">UF do CRO <span x-show="funcao === 'Dentista'" class="text-red-500">*</span></label>
                    <select name="cro_uf" x-bind:required="funcao === 'Dentista'" class="w-full rounded border-[1.5px] border-stroke bg-gray-2 py-3 px-5 text-sm text-black focus:border-primary focus:outline-none">
                        <option value="">Selecione</option>
                        @foreach(['AC','AL','AP','AM','BA','CE','DF','ES','GO','MA','MT','MS','MG','PA','PB','PR','PE','PI','RJ','RN','RS','RO','RR','SC','SP','SE','TO'] as $uf)
                            <option value="{{ $uf }}" @selected(old('cro_uf')===$uf)>{{ $uf }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </x-accordion-section>
    </div>
    <div x-show="activeTab === 'rem'" x-cloak class="space-y-6">
        <x-accordion-section title="Remuneração e Comissionamento" :open="true">
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div class="sm:col-span-2 flex space-x-2">
                    <div class="flex-1">
                        <label class="text-sm font-medium text-gray-700 mb-2 block">Salário fixo <span class="text-red-500">*</span></label>
                        <input type="text" name="salario_fixo" value="{{ old('salario_fixo') }}" class="currency-brl w-full rounded border-[1.5px] border-stroke bg-gray-2 py-3 px-5 text-sm text-black focus:border-primary focus:outline-none" required />
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-700 mb-2 block">&nbsp;</label>
                        <select name="salario_periodo" class="w-full rounded border-[1.5px] border-stroke bg-gray-2 py-3 px-5 text-sm text-black focus:border-primary focus:outline-none">
                            <option value="mes" @selected(old('salario_periodo')==='mes')>Por mês</option>
                            <option value="dia" @selected(old('salario_periodo')==='dia')>Por dia</option>
                            <option value="hora" @selected(old('salario_periodo')==='hora')>Por hora</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="space-y-4 mt-4">
                @foreach($clinics as $clinic)
                    <div class="p-4 bg-gray-50 border rounded" x-show="selectedClinics.includes('{{ $clinic->id }}')" x-cloak>
                        <h4 class="text-sm font-medium text-gray-700 mb-2">{{ $clinic->nome }}</h4>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="text-sm font-medium text-gray-700 mb-2 block">% de comissão</label>
                                <input type="number" step="0.01" min="0" max="100" name="comissoes[{{ $clinic->id }}][comissao]" value="{{ old('comissoes.' . $clinic->id . '.comissao') }}" class="w-full rounded border-[1.5px] border-stroke bg-gray-2 py-3 px-5 text-sm text-black focus:border-primary focus:outline-none" />
                            </div>
                            <div>
                                <label class="text-sm font-medium text-gray-700 mb-2 block">% prótese</label>
                                <input type="number" step="0.01" min="0" max="100" name="comissoes[{{ $clinic->id }}][protese]" value="{{ old('comissoes.' . $clinic->id . '.protese') }}" class="w-full rounded border-[1.5px] border-stroke bg-gray-2 py-3 px-5 text-sm text-black focus:border-primary focus:outline-none" />
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </x-accordion-section>
        <x-accordion-section title="Dados bancários">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="text-sm font-medium text-gray-700 mb-2 block">Nome do banco</label>
                    <input type="text" name="conta[nome_banco]" value="{{ old('conta.nome_banco') }}" class="w-full rounded border-[1.5px] border-stroke bg-gray-2 py-3 px-5 text-sm text-black focus:border-primary focus:outline-none" />
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-700 mb-2 block">Tipo de conta</label>
                    <select name="conta[tipo]" class="w-full rounded border-[1.5px] border-stroke bg-gray-2 py-3 px-5 text-sm text-black focus:border-primary focus:outline-none">
                        <option value="">Selecione</option>
                        <option value="Corrente" @selected(old('conta.tipo')==='Corrente')>Corrente</option>
                        <option value="Poupança" @selected(old('conta.tipo')==='Poupança')>Poupança</option>
                        <option value="Pagamento" @selected(old('conta.tipo')==='Pagamento')>Pagamento</option>
                    </select>
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-700 mb-2 block">Agência</label>
                    <input type="text" name="conta[agencia]" value="{{ old('conta.agencia') }}" class="w-full rounded border-[1.5px] border-stroke bg-gray-2 py-3 px-5 text-sm text-black focus:border-primary focus:outline-none" />
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-700 mb-2 block">Número da conta</label>
                    <input type="text" name="conta[numero]" value="{{ old('conta.numero') }}" class="w-full rounded border-[1.5px] border-stroke bg-gray-2 py-3 px-5 text-sm text-black focus:border-primary focus:outline-none" />
                </div>
                <div data-cpf-cnpj-group>
                    <label class="text-sm font-medium text-gray-700 mb-2 block">CPF/CNPJ do titular <span class="text-red-500" data-required-indicator>*</span></label>
                    <div class="flex items-center space-x-4 mb-2">
                        <label class="flex items-center space-x-1">
                            <input type="radio" name="conta[cpf_cnpj_tipo]" value="cpf" @checked(old('conta.cpf_cnpj_tipo', 'cpf')==='cpf') />
                            <span>CPF</span>
                        </label>
                        <label class="flex items-center space-x-1">
                            <input type="radio" name="conta[cpf_cnpj_tipo]" value="cnpj" @checked(old('conta.cpf_cnpj_tipo')==='cnpj') />
                            <span>CNPJ</span>
                        </label>
                    </div>
                    <input type="text" data-role="cpf_cnpj" name="conta[cpf_cnpj]" value="{{ old('conta.cpf_cnpj') }}" class="w-full rounded border-[1.5px] border-stroke bg-gray-2 py-3 px-5 text-sm text-black focus:border-primary focus:outline-none @error('conta.cpf_cnpj') border-red-500 @enderror" />
                </div>
                <div class="sm:col-span-2">
                    <label class="text-sm font-medium text-gray-700 mb-2 block">Chave PIX</label>
                    <input type="text" name="chave_pix" value="{{ old('chave_pix') }}" class="w-full rounded border-[1.5px] border-stroke bg-gray-2 py-3 px-5 text-sm text-black focus:border-primary focus:outline-none" />
                </div>
            </div>
        </x-accordion-section>
    </div>
        <div class="flex justify-between pt-4">
            <a href="{{ route('profissionais.index') }}" class="py-2 px-4 rounded border border-stroke text-gray-700">Cancelar</a>
            <button type="submit" class="py-2 px-4 bg-blue-600 text-white rounded hover:bg-blue-700">Salvar Profissional</button>
        </div>
    </form>
</div>
@endsection
