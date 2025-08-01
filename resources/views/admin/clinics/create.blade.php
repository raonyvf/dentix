@extends('layouts.app', ['hideErrors' => true])

@section('content')
@include('partials.breadcrumbs', ['crumbs' => [
    ['label' => 'Dashboard', 'url' => route('admin.index')],
    ['label' => 'Clínicas', 'url' => route('clinicas.index')],
    ['label' => 'Criar']
]])
<div class="w-full bg-white p-6 rounded-lg shadow">
    <h1 class="text-xl font-semibold mb-4">Criar Clínica</h1>
    @if ($errors->any())
        <x-alert-error>Por favor, preencha todos os campos obrigatórios (*)</x-alert-error>
    @endif
    <form method="POST" action="{{ route('clinicas.store') }}" class="space-y-6" novalidate>
        @csrf
        <div class="rounded-sm border border-stroke bg-gray-50 p-4">
            <h2 class="mb-4 text-sm font-medium text-gray-700">Dados da Clínica</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div class="sm:col-span-2">
                    <label class="mb-2 block text-sm font-medium text-gray-700">Nome *</label>
                    <input class="w-full rounded border-[1.5px] border-stroke bg-gray-2 py-3 px-5 text-sm text-black focus:border-primary focus:outline-none" type="text" name="nome" value="{{ old('nome') }}" required />
                </div>
                <div>
                    <label class="mb-2 block text-sm font-medium text-gray-700">CNPJ *</label>
                    <input class="w-full rounded border-[1.5px] border-stroke bg-gray-2 py-3 px-5 text-sm text-black focus:border-primary focus:outline-none" type="text" name="cnpj" value="{{ old('cnpj') }}" required />
                </div>
            </div>
        </div>

        <div class="rounded-sm border border-stroke bg-gray-50 p-4">
            <h2 class="mb-4 text-sm font-medium text-gray-700">Responsável Técnico</h2>
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                    <label class="mb-2 block text-sm font-medium text-gray-700">Nome *</label>
                    <input class="w-full rounded border-[1.5px] border-stroke bg-gray-2 py-3 px-5 text-sm text-black focus:border-primary focus:outline-none" type="text" name="responsavel_first_name" value="{{ old('responsavel_first_name') }}" required />
                </div>
                <div>
                    <label class="mb-2 block text-sm font-medium text-gray-700">Nome do Meio *</label>
                    <input class="w-full rounded border-[1.5px] border-stroke bg-gray-2 py-3 px-5 text-sm text-black focus:border-primary focus:outline-none" type="text" name="responsavel_middle_name" value="{{ old('responsavel_middle_name') }}" required />
                </div>
                <div>
                    <label class="mb-2 block text-sm font-medium text-gray-700">Último *</label>
                    <input class="w-full rounded border-[1.5px] border-stroke bg-gray-2 py-3 px-5 text-sm text-black focus:border-primary focus:outline-none" type="text" name="responsavel_last_name" value="{{ old('responsavel_last_name') }}" required />
                </div>
                <div class="sm:col-span-3">
                    <label class="mb-2 block text-sm font-medium text-gray-700">CRO *</label>
                    <input class="w-full rounded border-[1.5px] border-stroke bg-gray-2 py-3 px-5 text-sm text-black focus:border-primary focus:outline-none" type="text" name="cro" value="{{ old('cro') }}" required />
                </div>
            </div>
        </div>
        <div class="rounded-sm border border-stroke bg-gray-50 p-4">
            <h2 class="mb-4 text-sm font-medium text-gray-700">Endereço</h2>
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                <div>
                    <label class="mb-2 block text-sm font-medium text-gray-700">CEP *</label>
                    <input class="w-full rounded border-[1.5px] border-stroke bg-gray-2 py-3 px-5 text-sm text-black focus:border-primary focus:outline-none" type="text" name="cep" value="{{ old('cep') }}" required />
                </div>
                <div class="sm:col-span-2">
                    <label class="mb-2 block text-sm font-medium text-gray-700">Logradouro *</label>
                    <input class="w-full rounded border-[1.5px] border-stroke bg-gray-2 py-3 px-5 text-sm text-black focus:border-primary focus:outline-none" type="text" name="logradouro" value="{{ old('logradouro') }}" required />
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
                    <label class="mb-2 block text-sm font-medium text-gray-700">Cidade *</label>
                    <input class="w-full rounded border-[1.5px] border-stroke bg-gray-2 py-3 px-5 text-sm text-black focus:border-primary focus:outline-none" type="text" name="cidade" value="{{ old('cidade') }}" required />
                </div>
                <div>
                    <label class="mb-2 block text-sm font-medium text-gray-700">Estado *</label>
                    <input class="w-full rounded border-[1.5px] border-stroke bg-gray-2 py-3 px-5 text-sm text-black focus:border-primary focus:outline-none" type="text" name="estado" value="{{ old('estado') }}" required />
                </div>
            </div>
        </div>
        <div class="rounded-sm border border-stroke bg-gray-50 p-4">
            <h2 class="mb-4 text-sm font-medium text-gray-700">Contato</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="mb-2 block text-sm font-medium text-gray-700">Telefone *</label>
                    <input class="w-full rounded border-[1.5px] border-stroke bg-gray-2 py-3 px-5 text-sm text-black focus:border-primary focus:outline-none" type="text" name="telefone" value="{{ old('telefone') }}" required />
                </div>
                <div>
                    <label class="mb-2 block text-sm font-medium text-gray-700">E-mail *</label>
                    <input class="w-full rounded border-[1.5px] border-stroke bg-gray-2 py-3 px-5 text-sm text-black focus:border-primary focus:outline-none" type="email" name="email" value="{{ old('email') }}" required />
                </div>
            </div>
        </div>
        <div>
            <label class="mb-2 block text-sm font-medium text-gray-700">Horários de Funcionamento *</label>
            <div class="mb-2">
                <button type="button" id="apply-schedule-all" class="px-3 py-1 bg-blue-600 text-white rounded hover:bg-blue-700 text-sm">Aplicar para selecionados</button>
            </div>
            @php
                $diasSemana = [
                    'segunda' => 'Segunda-feira',
                    'terca' => 'Terça-feira',
                    'quarta' => 'Quarta-feira',
                    'quinta' => 'Quinta-feira',
                    'sexta' => 'Sexta-feira',
                    'sabado' => 'Sábado',
                    'domingo' => 'Domingo',
                ];
            @endphp
            @foreach ($diasSemana as $diaKey => $diaLabel)
                <div class="flex items-center space-x-2 mb-2">
                    <input type="checkbox" class="day-select" data-dia="{{ $diaKey }}">
                    <span class="w-40 text-sm whitespace-nowrap flex-shrink-0">{{ $diaLabel }}</span>
                    <input type="time" name="horarios[{{ $diaKey }}][abertura]" value="{{ old('horarios.' . $diaKey . '.abertura') }}" class="w-full rounded border-[1.5px] border-stroke bg-gray-2 py-2 px-3 text-sm text-black focus:border-primary focus:outline-none" />
                    <input type="time" name="horarios[{{ $diaKey }}][fechamento]" value="{{ old('horarios.' . $diaKey . '.fechamento') }}" class="w-full rounded border-[1.5px] border-stroke bg-gray-2 py-2 px-3 text-sm text-black focus:border-primary focus:outline-none" />
                </div>
            @endforeach
        </div>
        <button type="submit" class="py-2 px-4 bg-blue-600 text-white rounded hover:bg-blue-700">Salvar</button>
    </form>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {

        const applyBtn = document.getElementById('apply-schedule-all');
        if (applyBtn) {
            applyBtn.addEventListener('click', () => {
                const startVal = document.querySelector('input[name="horarios[segunda][abertura]"]').value;
                const endVal = document.querySelector('input[name="horarios[segunda][fechamento]"]').value;
                if (!startVal || !endVal) return;
                document.querySelectorAll('.day-select').forEach(cb => {
                    const dia = cb.dataset.dia;
                    if (dia !== 'segunda' && cb.checked) {
                        const a = document.querySelector(`input[name="horarios[${dia}][abertura]"]`);
                        const f = document.querySelector(`input[name="horarios[${dia}][fechamento]"]`);
                        if (a && f) {
                            a.value = startVal;
                            f.value = endVal;
                        }
                    }
                });
            });
        }
    });
</script>
@endsection
