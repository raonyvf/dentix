@extends('layouts.app')

@section('content')
@include('partials.breadcrumbs', ['crumbs' => [
    ['label' => 'Dashboard', 'url' => route('admin.index')],
    ['label' => 'Profissionais']
]])
@php
    $total = $users->count();
    $dentistas = $users->where('dentista', true)->count();
    $auxiliares = $total - $dentistas;
@endphp
<div class="mb-6 flex items-start justify-between">
    <div>
        <h1 class="text-2xl font-bold">Profissionais</h1>
        <p class="text-gray-600">Gestão de profissionais e funcionários</p>
    </div>
    <div class="flex items-center space-x-2">
        <a href="{{ route('profissionais.create') }}" class="py-2 px-4 bg-blue-600 text-white rounded hover:bg-blue-700 flex items-center">
            + Novo Profissional
        </a>
        <div x-data="{ open: false }" class="relative">
            <button @click="open = !open" type="button" class="py-2 px-4 bg-white border rounded flex items-center text-sm hover:bg-gray-50">
                Relatórios
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                </svg>
            </button>
            <div x-show="open" @click.away="open = false" x-cloak class="absolute right-0 mt-2 w-40 bg-white border rounded shadow">
                <a href="#" class="block px-4 py-2 text-sm hover:bg-gray-100">Exportar CSV</a>
            </div>
        </div>
    </div>
</div>
<div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
    <div class="rounded-sm border border-stroke bg-white p-5 shadow">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500">Total de Profissionais</p>
                <p class="mt-1 text-xl font-bold text-black">{{ $total }}</p>
            </div>
            <div class="flex h-11 w-11 items-center justify-center rounded-full bg-primary/10 text-primary">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a4 4 0 00-4-4h-1M9 20H4v-2a4 4 0 014-4h1m4-8a4 4 0 110 8 4 4 0 010-8z" />
                </svg>
            </div>
        </div>
        <p class="mt-2 text-xs text-gray-500">{{ $dentistas . ' Dentistas | ' . $auxiliares . ' Auxiliares' }}</p>
    </div>
    <div class="rounded-sm border border-stroke bg-white p-5 shadow">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500">Atendimentos (último mês)</p>
                <p class="mt-1 text-xl font-bold text-black">{{ $atendimentosMes ?? 0 }}</p>
            </div>
            <div class="flex h-11 w-11 items-center justify-center rounded-full bg-primary/10 text-primary">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
            </div>
        </div>
        @if($variacaoAtendimentos ?? null)
            <p class="mt-2 text-xs text-gray-500">{{ $variacaoAtendimentos }}</p>
        @endif
    </div>
    <div class="rounded-sm border border-stroke bg-white p-5 shadow">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500">Comissões (total do mês)</p>
                <p class="mt-1 text-xl font-bold text-black">{{ isset($totalComissoes) ? 'R$ '.number_format($totalComissoes, 2, ',', '.') : 'R$ 0,00' }}</p>
            </div>
            <div class="flex h-11 w-11 items-center justify-center rounded-full bg-primary/10 text-primary">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 1.343-3 3s1.343 3 3 3 3 1.343 3 3-1.343 3-3 3m0-12V4m0 16v-4m0 4c1.657 0 3-1.343 3-3s-1.343-3-3-3-3-1.343-3-3 1.343-3 3-3" />
                </svg>
            </div>
        </div>
        @if(isset($mediaComissao))
            <p class="mt-2 text-xs text-gray-500">Média por profissional: R$ {{ number_format($mediaComissao, 2, ',', '.') }}</p>
        @endif
    </div>
</div>
<form method="GET" class="mb-4">
    <select name="clinica_id" onchange="this.form.submit()" class="border rounded px-3 py-2 text-sm">
        <option value="">Todas as Clínicas</option>
        @foreach(($clinics ?? []) as $clinic)
            <option value="{{ $clinic->id }}" @selected(request('clinica_id') == $clinic->id)>{{ $clinic->nome }}</option>
        @endforeach
    </select>
</form>
<div class="bg-white rounded-lg shadow">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200 text-sm">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-2 text-left font-medium text-gray-500 uppercase">Profissional</th>
                    <th class="px-4 py-2 text-left font-medium text-gray-500 uppercase">Cargo</th>
                    <th class="px-4 py-2 text-left font-medium text-gray-500 uppercase">Clínicas</th>
                    <th class="px-4 py-2 text-left font-medium text-gray-500 uppercase">Contato</th>
                    <th class="px-4 py-2 text-center font-medium text-gray-500 uppercase">Ações</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse ($users as $profissional)
                    <tr>
                        <td class="px-4 py-2">
                            <div class="flex items-center space-x-2">
                                <img src="{{ $profissional->photo_path ? asset('storage/'.$profissional->photo_path) : 'data:image/svg+xml,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20width%3D%2240%22%20height%3D%2240%22%3E%3Crect%20width%3D%2240%22%20height%3D%2240%22%20fill%3D%22%23ddd%22%2F%3E%3C%2Fsvg%3E' }}" class="w-10 h-10 rounded-full object-cover" alt="Foto">
                                <div>
                                    <div class="font-medium text-gray-700">{{ $profissional->name }}</div>
                                    @if($profissional->especialidade)
                                        <div class="text-xs text-gray-500">{{ $profissional->especialidade }}</div>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td class="px-4 py-2">{{ $profissional->cargo ?? ($profissional->dentista ? 'Dentista' : 'Auxiliar') }}</td>
                        <td class="px-4 py-2 space-x-1">
                            @php
                                $clinicas = $profissional->horariosProfissional
                                    ->map(fn($h) => $h->clinic)
                                    ->filter()
                                    ->unique('id');
                            @endphp
                            @foreach($clinicas as $c)
                                <span class="inline-block px-2 py-1 bg-gray-100 rounded text-xs">{{ $c->nome }}</span>
                            @endforeach
                        </td>
                        <td class="px-4 py-2">
                            <div>{{ $profissional->email }}</div>
                            <div class="text-xs text-gray-500">{{ $profissional->phone }}</div>
                        </td>
                        <td class="px-4 py-2">
                            <div class="flex items-center justify-center space-x-2">
                                <a href="{{ route('profissionais.show', ['profissional' => $profissional->id]) }}" class="text-gray-600 hover:text-blue-600" title="Ver Perfil">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A13.937 13.937 0 0112 15c2.33 0 4.5.533 6.879 1.532M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                </a>
                                <a href="#" class="text-gray-600 hover:text-blue-600" title="Ver Agenda">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                </a>
                                <a href="#" class="text-gray-600 hover:text-blue-600" title="Ver Pagamentos">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 1.343-3 3s1.343 3 3 3 3 1.343 3 3-1.343 3-3 3m0-12V4m0 16v-4m0 4c1.657 0 3-1.343 3-3s-1.343-3-3-3-3-1.343-3-3 1.343-3 3-3" />
                                    </svg>
                                </a>
                                <a href="{{ route('profissionais.edit', ['profissional' => $profissional->id]) }}" class="text-gray-600 hover:text-blue-600" title="Editar">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m0 0a2.5 2.5 0 01-3.536 3.536L9 20.036l-4 1 1-4 6.232-6.232a2.5 2.5 0 013.536-3.536z" />
                                    </svg>
                                </a>
                                <form action="{{ route('profissionais.destroy', ['profissional' => $profissional->id]) }}" method="POST" onsubmit="return confirm('Tem certeza que deseja excluir este profissional?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-800" title="Excluir">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6M1 7h22M10 7V4a1 1 0 011-1h2a1 1 0 011 1v3" />
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-4 py-2 text-center">Nenhum profissional cadastrado.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if(method_exists($users, 'links'))
        <div class="p-4">
            {{ $users->links() }}
        </div>
    @endif
</div>
@endsection
