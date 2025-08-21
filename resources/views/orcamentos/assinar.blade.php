@extends('layouts.app')

@section('content')
@php
    $status = $status ?? 'pendente';
    $validade = $validade ?? '22/04/2024';
    $paciente = $paciente ?? 'João Santos Jr.';
    $responsavel = $responsavel ?? ['nome' => 'Maria Santos', 'cpf' => '123.456.789-00'];
    $itens = $itens ?? [
        ['nome' => 'Instalação de Aparelho Ortodôntico', 'valor' => 'R$ 2.500,00'],
        ['nome' => 'Manutenções (24x)', 'valor' => 'R$ 6.000,00'],
        ['nome' => 'Documentação Ortodôntica', 'valor' => 'R$ 500,00'],
        ['nome' => 'Total', 'valor' => 'R$ 8.500,00', 'total' => true],
        ['nome' => 'Desconto Convênio', 'valor' => '-R$ 850,00', 'desconto' => true],
        ['nome' => 'Valor Final', 'valor' => 'R$ 7.650,00', 'final' => true],
    ];
@endphp

<div class="max-w-4xl mx-auto space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between">
        <div>
            <h1 class="text-2xl font-semibold text-gray-700">Assinatura de Orçamento</h1>
            <p class="text-sm text-gray-500">Revise e assine o orçamento do tratamento</p>
        </div>
        <a href="#" class="mt-4 sm:mt-0 inline-flex items-center px-4 py-2 border border-primary text-primary rounded hover:bg-primary/10">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v16h16V4H4zm8 10v-6m0 6l-3-3m3 3l3-3" />
            </svg>
            Baixar PDF
        </a>
    </div>

    <div class="bg-white rounded-lg shadow p-6" x-data="{ accepted: false }">
        <div class="mb-6">
            <div class="flex items-center gap-2">
                <h2 class="text-xl font-semibold text-gray-700">Detalhes do Orçamento</h2>
                @if($status === 'aprovado')
                    <span class="px-2 py-0.5 rounded-full text-xs bg-emerald-100 text-emerald-800">Aprovado</span>
                @elseif($status === 'expirado')
                    <span class="px-2 py-0.5 rounded-full text-xs bg-red-100 text-red-800">Expirado</span>
                @endif
            </div>
            <p class="text-sm mt-1 @if($status === 'expirado') text-red-600 @else text-gray-500 @endif">Orçamento válido até {{ $validade }}</p>
        </div>

        <div class="mb-6 grid grid-cols-1 @if($responsavel) md:grid-cols-2 @endif divide-y md:divide-y-0 md:divide-x divide-gray-200 text-sm">
            <div class="py-2 md:pr-4">
                <span class="block text-gray-500">Paciente</span>
                <p class="font-medium text-gray-900">{{ $paciente }}</p>
            </div>
            @if($responsavel)
            <div class="py-2 md:pl-4">
                <span class="block text-gray-500">Responsável legal</span>
                <p class="font-medium text-gray-900">{{ $responsavel['nome'] }} <span class="text-gray-500">• CPF {{ $responsavel['cpf'] }}</span></p>
            </div>
            @endif
        </div>

        <div class="mb-6">
            <div class="hidden sm:block overflow-x-auto">
                <table class="w-full text-sm">
                    <tbody class="divide-y divide-gray-200">
                        @foreach($itens as $item)
                            <tr>
                                <td class="py-2 {{ $item['final'] ?? false ? 'font-semibold' : ($item['total'] ?? false ? 'font-medium' : '') }} {{ ($item['desconto'] ?? false) ? 'text-emerald-600' : '' }}">{{ $item['nome'] }}</td>
                                <td class="py-2 text-right whitespace-nowrap {{ $item['final'] ?? false ? 'font-semibold text-base text-gray-900' : '' }} {{ ($item['total'] ?? false) ? 'font-medium' : '' }} {{ ($item['desconto'] ?? false) ? 'text-emerald-600' : '' }}">{{ $item['valor'] }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="sm:hidden divide-y divide-gray-200 text-sm">
                @foreach($itens as $item)
                    <div class="flex justify-between py-2">
                        <span class="{{ ($item['final'] ?? false) ? 'font-semibold' : (($item['total'] ?? false) ? 'font-medium' : '') }} {{ ($item['desconto'] ?? false) ? 'text-emerald-600' : '' }}">{{ $item['nome'] }}</span>
                        <span class="{{ ($item['final'] ?? false) ? 'font-semibold text-gray-900' : '' }} {{ ($item['total'] ?? false) ? 'font-medium' : '' }} {{ ($item['desconto'] ?? false) ? 'text-emerald-600' : '' }}">{{ $item['valor'] }}</span>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="mb-6 flex items-start bg-gray-50 border border-gray-200 rounded p-4">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-gray-500 mr-3 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M12 2a10 10 0 100 20 10 10 0 000-20z" />
            </svg>
            <p class="text-sm text-gray-600">Ao assinar este orçamento, você concorda com os termos do tratamento, forma de pagamento e prazo estabelecido. O orçamento tem validade de 30 dias a partir da data de emissão.</p>
        </div>

        <div class="mb-6">
            <label class="inline-flex items-start">
                <input type="checkbox" x-model="accepted" class="mt-1 rounded border-gray-300 text-primary focus:ring-primary">
                <span class="ml-2 text-sm text-gray-700">Li e aceito os termos do tratamento</span>
            </label>
        </div>

        <div class="flex justify-end space-x-3">
            <a href="#" class="px-4 py-2 border border-gray-300 text-gray-700 rounded hover:bg-gray-50">Voltar</a>
            <button type="button" :disabled="!accepted" @if($status !== 'pendente') disabled @endif class="inline-flex items-center px-4 py-2 bg-primary text-white rounded hover:bg-primary/90 disabled:opacity-50 disabled:cursor-not-allowed">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
                Assinar e Aprovar
            </button>
        </div>
    </div>
</div>
@endsection
