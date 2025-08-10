@php
    $orcamentos = [
        ['id' => 'O001', 'data' => '22/03/2024', 'valor' => 'R$ 7.650,00', 'status' => 'Aprovado'],
        ['id' => 'O002', 'data' => '02/07/2024', 'valor' => 'R$ 5.300,00', 'status' => 'Pendente'],
    ];
    $statusColors = [
        'Aprovado' => 'bg-emerald-100 text-emerald-800',
        'Pendente' => 'bg-orange-100 text-orange-800',
        'Recusado' => 'bg-red-100 text-red-800',
    ];
@endphp
<div class="space-y-4">
    <div class="flex items-center justify-between">
        <div class="flex space-x-2">
            <input type="text" placeholder="Buscar..." class="px-3 py-2 border rounded">
            <select class="px-3 py-2 border rounded">
                <option value="">Todos status</option>
                <option>Aprovado</option>
                <option>Pendente</option>
                <option>Recusado</option>
            </select>
        </div>
        <a href="#" class="inline-flex items-center px-4 py-2 bg-emerald-600 text-white rounded hover:bg-emerald-700">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            Gerar Novo Orçamento
        </a>
    </div>
    <div class="overflow-x-auto bg-white rounded-lg shadow">
        <table class="min-w-full divide-y divide-gray-200 text-sm">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-2 text-left font-medium text-gray-500 uppercase">ID</th>
                    <th class="px-4 py-2 text-left font-medium text-gray-500 uppercase">Data</th>
                    <th class="px-4 py-2 text-left font-medium text-gray-500 uppercase">Valor</th>
                    <th class="px-4 py-2 text-left font-medium text-gray-500 uppercase">Status</th>
                    <th class="px-4 py-2 text-left font-medium text-gray-500 uppercase">Ações</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @foreach ($orcamentos as $o)
                    <tr>
                        <td class="px-4 py-2 whitespace-nowrap">{{ $o['id'] }}</td>
                        <td class="px-4 py-2 whitespace-nowrap">{{ $o['data'] }}</td>
                        <td class="px-4 py-2 whitespace-nowrap">{{ $o['valor'] }}</td>
                        <td class="px-4 py-2 whitespace-nowrap">
                            <span class="px-2 py-1 rounded-full text-xs {{ $statusColors[$o['status']] ?? 'bg-gray-100 text-gray-800' }}">{{ $o['status'] }}</span>
                        </td>
                        <td class="px-4 py-2 whitespace-nowrap">
                            <a href="#" class="text-gray-600 hover:text-blue-600" title="Visualizar">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5s8.268 2.943 9.542 7c-1.274 4.057-5.065 7-9.542 7s-8.268-2.943-9.542-7z" />
                                </svg>
                            </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

