@props(['titulo', 'dataGeracao', 'validade', 'valorTotal', 'desconto', 'valorFinal'])
<div class="bg-white rounded-lg shadow p-6 flex flex-col sm:flex-row justify-between items-start sm:items-center space-y-4 sm:space-y-0">
    <div class="space-y-1">
        <h3 class="text-lg font-semibold text-gray-800">{{ $titulo }}</h3>
        <p class="text-sm text-gray-500">Gerado em: {{ $dataGeracao }}</p>
        <p class="text-sm text-gray-500">Validade: {{ $validade }}</p>
        <p class="text-sm text-gray-700">Valor Total: <span class="font-medium text-gray-900">{{ $valorTotal }}</span></p>
        <p class="text-sm text-emerald-600">Desconto Convênio: {{ $desconto }}</p>
        <p class="text-sm text-gray-700">Valor Final: <span class="font-medium text-gray-900">{{ $valorFinal }}</span></p>
    </div>
    <a href="#" class="inline-flex items-center px-4 py-2 bg-primary text-white rounded hover:bg-primary/90">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 20h9" />
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16.5 3.5a3 3 0 11-4.5 4.5L5 15v4h4l7-7a3 3 0 014.5-4.5z" />
        </svg>
        Assinar (Responsável)
    </a>
</div>
