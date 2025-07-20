@extends('layouts.app')

@section('content')
<div class="flex items-start justify-between mb-6">
    <div>
        <h1 class="text-2xl font-semibold text-gray-700">Assinatura de Orçamento</h1>
        <p class="text-sm text-gray-500">Revise e assine o orçamento do tratamento</p>
    </div>
    <a href="#" class="inline-flex items-center px-4 py-2 bg-primary text-white rounded hover:bg-primary/90">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v16h16V4H4zm8 10v-6m0 6l-3-3m3 3l3-3" />
        </svg>
        Baixar PDF
    </a>
</div>
<div class="bg-white rounded-lg shadow p-6" x-data="{ accepted: false }">
    <div class="mb-6">
        <h2 class="text-xl font-semibold text-gray-700">Detalhes do Orçamento</h2>
        <p class="text-sm text-gray-500">Orçamento válido até 22/04/2024</p>
    </div>
    <div class="mb-6 grid grid-cols-1 sm:grid-cols-3 gap-4 text-sm">
        <div>
            <span class="text-gray-500">Nome do paciente</span>
            <p class="text-gray-900 font-medium">João Santos Jr.</p>
        </div>
        <div>
            <span class="text-gray-500">Responsável legal</span>
            <p class="text-gray-900 font-medium">Maria Santos</p>
        </div>
        <div>
            <span class="text-gray-500">CPF</span>
            <p class="text-gray-900 font-medium">123.456.789-00</p>
        </div>
    </div>
    <div class="mb-6 overflow-x-auto">
        <table class="w-full text-sm">
            <tbody class="divide-y divide-gray-200">
                <tr>
                    <td class="py-2">Instalação de Aparelho Ortodôntico</td>
                    <td class="py-2 text-right whitespace-nowrap">R$ 2.500,00</td>
                </tr>
                <tr>
                    <td class="py-2">Manutenções (24x)</td>
                    <td class="py-2 text-right whitespace-nowrap">R$ 6.000,00</td>
                </tr>
                <tr>
                    <td class="py-2">Documentação Ortodôntica</td>
                    <td class="py-2 text-right whitespace-nowrap">R$ 500,00</td>
                </tr>
                <tr>
                    <td class="py-2 font-medium">Total</td>
                    <td class="py-2 text-right whitespace-nowrap font-medium">R$ 8.500,00</td>
                </tr>
                <tr>
                    <td class="py-2 text-emerald-600">Desconto Convênio</td>
                    <td class="py-2 text-right whitespace-nowrap text-emerald-600">-R$ 850,00</td>
                </tr>
                <tr>
                    <td class="py-2 font-semibold">Valor Final</td>
                    <td class="py-2 text-right whitespace-nowrap font-semibold text-gray-900">R$ 7.650,00</td>
                </tr>
            </tbody>
        </table>
    </div>
    <div class="mb-6">
        <h3 class="text-sm font-medium text-gray-700 mb-2">Termos e Condições</h3>
        <p class="text-sm text-gray-600 mb-4">Ao assinar este orçamento, você concorda com os termos do tratamento, forma de pagamento e prazo estabelecido. O orçamento tem validade de 30 dias a partir da data de emissão.</p>
        <label class="inline-flex items-start">
            <input type="checkbox" x-model="accepted" class="mt-1 rounded border-gray-300 text-primary focus:ring-primary">
            <span class="ml-2 text-sm text-gray-700">Li e aceito os termos do tratamento</span>
        </label>
    </div>
    <div class="flex items-center justify-between">
        <a href="#" class="px-4 py-2 bg-gray-200 text-gray-700 rounded hover:bg-gray-300">Voltar</a>
        <button type="button" :disabled="!accepted" class="inline-flex items-center px-4 py-2 bg-emerald-600 text-white rounded hover:bg-emerald-700 disabled:opacity-50">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
            </svg>
            Assinar e Aprovar
        </button>
    </div>
</div>
@endsection
