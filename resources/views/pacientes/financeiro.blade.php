@php

    $pagamentos = [
        ['data' => '05/04/2024', 'valor' => 'R$ 2.000,00', 'forma' => 'Cartão', 'parcela' => '1/3', 'status' => 'Pago'],
        ['data' => '05/05/2024', 'valor' => 'R$ 2.000,00', 'forma' => 'PIX', 'parcela' => '2/3', 'status' => 'Pago'],
        ['data' => '05/06/2024', 'valor' => 'R$ 2.000,00', 'forma' => 'PIX', 'parcela' => '3/3', 'status' => 'Pendente'],
    ];

    $faturas = [
        ['id' => 'F001', 'valor' => 'R$ 2.000,00', 'status' => 'Pago', 'data' => '05/04/2024'],
        ['id' => 'F002', 'valor' => 'R$ 2.000,00', 'status' => 'Pago', 'data' => '05/05/2024'],
        ['id' => 'F003', 'valor' => 'R$ 2.000,00', 'status' => 'Aberto', 'data' => '05/06/2024'],
    ];

    $estornos = [
        ['data' => '10/05/2024', 'valor' => 'R$ 100,00', 'motivo' => 'Ajuste de cobrança', 'metodo' => 'PIX'],
    ];

    $statusColors = [
        'Aprovado' => 'bg-emerald-100 text-emerald-800',
        'Pendente' => 'bg-orange-100 text-orange-800',
        'Recusado' => 'bg-red-100 text-red-800',
        'Pago' => 'bg-emerald-100 text-emerald-800',
        'Vencido' => 'bg-red-100 text-red-800',
        'Aberto' => 'bg-orange-100 text-orange-800',
    ];
@endphp
<div class="space-y-6">
    <div>
        <h2 class="text-xl font-semibold text-gray-700">Resumo Financeiro</h2>
        <p class="text-sm text-gray-500">{{ $paciente->pessoa->first_name }} {{ $paciente->pessoa->last_name }} • ID {{ $paciente->id }}</p>
    </div>
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <x-financeiro.card
            label="Valor do orçamento aprovado"
            value="R$ 7.650,00"
            color="bg-blue-500"
            icon="<svg xmlns='http://www.w3.org/2000/svg' class='w-6 h-6' fill='none' viewBox='0 0 24 24' stroke='currentColor'><path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M12 8c-3.314 0-6 1.343-6 3s2.686 3 6 3 6-1.343 6-3-2.686-3-6-3z'/><path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M6 11v5c0 1.657 2.686 3 6 3s6-1.343 6-3v-5'/></svg>" />
        <x-financeiro.card
            label="Valor total pago"
            value="R$ 4.000,00"
            color="bg-emerald-500"
            icon="<svg xmlns='http://www.w3.org/2000/svg' class='w-6 h-6' fill='none' viewBox='0 0 24 24' stroke='currentColor'><path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M12 8c-3.314 0-6 1.343-6 3s2.686 3 6 3 6-1.343 6-3-2.686-3-6-3z'/><path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M6 11v5c0 1.657 2.686 3 6 3s6-1.343 6-3v-5'/></svg>" />
        <x-financeiro.card
            label="Saldo devedor"
            value="R$ 3.650,00"
            color="bg-red-500"
            icon="<svg xmlns='http://www.w3.org/2000/svg' class='w-6 h-6' fill='none' viewBox='0 0 24 24' stroke='currentColor'><path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M12 8c-3.314 0-6 1.343-6 3s2.686 3 6 3 6-1.343 6-3-2.686-3-6-3z'/><path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M6 11v5c0 1.657 2.686 3 6 3s6-1.343 6-3v-5'/></svg>" />
    </div>
    <div class="space-y-4">
        <div class="flex items-center justify-between">
            <h3 class="text-lg font-semibold text-gray-700">Pagamentos</h3>
            <a href="#" class="inline-flex items-center px-3 py-2 bg-primary text-white rounded hover:bg-primary/90 text-sm">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                Adicionar Pagamento
            </a>
        </div>
        <x-financeiro.table :headings="['Data', 'Valor', 'Forma', 'Parcela', 'Status', 'Ações']">
            @foreach ($pagamentos as $p)
                <tr>
                    <td class="px-4 py-2">{{ $p['data'] }}</td>
                    <td class="px-4 py-2">{{ $p['valor'] }}</td>
                    <td class="px-4 py-2">{{ $p['forma'] }}</td>
                    <td class="px-4 py-2">{{ $p['parcela'] }}</td>
                    <td class="px-4 py-2">
                        @php $color = $statusColors[$p['status']] ?? 'bg-gray-100 text-gray-800'; @endphp
                        <span class="px-2 py-1 rounded-full text-xs {{ $color }}">{{ $p['status'] }}</span>
                    </td>
                    <td class="px-4 py-2 space-x-2">
                        <a href="#" class="text-gray-600 hover:text-blue-600" title="Editar">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 inline" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m0 0a2.5 2.5 0 01-3.536 3.536L9 20.036l-4 1 1-4 6.232-6.232a2.5 2.5 0 013.536-3.536z" />
                            </svg>
                        </a>
                        <a href="#" class="text-red-600 hover:text-red-800" title="Excluir">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 inline" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6M1 7h22M10 7V4a1 1 0 011-1h2a1 1 0 011 1v3" />
                            </svg>
                        </a>
                    </td>
                </tr>
            @endforeach
        </x-financeiro.table>
    </div>
    <div class="space-y-4">
        <h3 class="text-lg font-semibold text-gray-700">Faturas e Recibos</h3>
        <x-financeiro.table :headings="['ID', 'Valor', 'Status', 'Recibo', 'Data']">
            @foreach ($faturas as $f)
                <tr>
                    <td class="px-4 py-2">{{ $f['id'] }}</td>
                    <td class="px-4 py-2">{{ $f['valor'] }}</td>
                    <td class="px-4 py-2"><span class="px-2 py-1 rounded-full text-xs {{ $statusColors[$f['status']] ?? '' }}">{{ $f['status'] }}</span></td>
                    <td class="px-4 py-2">
                        <a href="#" class="text-gray-600 hover:text-blue-600" title="Baixar PDF">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 inline" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v16h16V4H4zm8 10v-6m0 6l-3-3m3 3l3-3" />
                            </svg>
                        </a>
                    </td>
                    <td class="px-4 py-2">{{ $f['data'] }}</td>
                </tr>
            @endforeach
        </x-financeiro.table>
    </div>
    <div class="space-y-4">
        <h3 class="text-lg font-semibold text-gray-700">Estornos ou Reembolsos</h3>
        <x-financeiro.table :headings="['Data', 'Valor', 'Motivo', 'Método']">
            @foreach ($estornos as $e)
                <tr>
                    <td class="px-4 py-2">{{ $e['data'] }}</td>
                    <td class="px-4 py-2">{{ $e['valor'] }}</td>
                    <td class="px-4 py-2">{{ $e['motivo'] }}</td>
                    <td class="px-4 py-2">{{ $e['metodo'] }}</td>
                </tr>
            @endforeach
        </x-financeiro.table>
    </div>
    <div class="space-y-2">
        <h3 class="text-lg font-semibold text-gray-700">Convênio</h3>
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 text-sm">
            <div>
                <span class="text-gray-500">Nome do convênio</span>
                <p class="text-gray-900">Unimed</p>
            </div>
            <div>
                <span class="text-gray-500">Carteirinha</span>
                <p class="text-gray-900">1234567890</p>
            </div>
            <div>
                <span class="text-gray-500">Coparticipação</span>
                <p class="text-gray-900">20%</p>
            </div>
        </div>
    </div>
    <div>
        <h3 class="text-lg font-semibold text-gray-700 mb-2">Observações Financeiras</h3>
        <textarea class="w-full rounded border-stroke bg-gray-2 p-3 text-sm text-black focus:border-primary focus:outline-none" rows="4" placeholder="Anotações internas"></textarea>
    </div>
</div>
