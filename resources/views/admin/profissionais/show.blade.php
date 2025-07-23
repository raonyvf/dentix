@extends('layouts.app')

@section('content')
@include('partials.breadcrumbs', ['crumbs' => [
    ['label' => 'Dashboard', 'url' => route('admin.index')],
    ['label' => 'Profissionais', 'url' => route('profissionais.index')],
    ['label' => 'Detalhes']
]])
<div class="flex items-center justify-between mb-6">
    <div class="flex items-center">
        <div>
            <h1 class="text-2xl font-bold">Detalhes do Profissional</h1>
            <p class="text-gray-600">Gerencie todas as informações e configurações do profissional</p>
        </div>
    </div>
    <div class="space-x-2">
        <a href="#" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Comissao</a>
        <a href="#" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Comissões</a>
    </div>
</div>
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <div class="space-y-4 lg:col-span-1">
        <div class="bg-white rounded shadow p-4">
            <div class="flex flex-col items-center text-center space-y-2">
                @if($profissional->photo_path)
                    <img src="{{ asset('storage/'.$profissional->photo_path) }}" class="w-20 h-20 rounded-full object-cover" alt="Foto">
                @else
                    @php
                        $initials = collect(explode(' ', $profissional->name))
                            ->filter()
                            ->map(fn($part) => strtoupper(substr($part, 0, 1)))
                            ->take(2)
                            ->implode('');
                    @endphp
                    <div class="w-20 h-20 rounded-full bg-gray-300 flex items-center justify-center text-lg font-medium text-gray-700">
                        {{ $initials }}
                    </div>
                @endif
                <h2 class="text-lg font-semibold">{{ $profissional->name }}</h2>
                <p class="text-sm text-gray-500">{{ $profissional->especialidade }}</p>
            </div>
            @php $cp = $profissional->clinicasProfissional->first(); @endphp
            <div class="mt-4 space-y-1 text-sm">
                <span class="inline-block px-2 py-1 rounded {{ ($cp?->status ?? 'Ativo') == 'Ativo' ? 'bg-emerald-100 text-emerald-700' : 'bg-red-100 text-red-700' }}">{{ $cp?->status ?? 'Ativo' }}</span>
                <div>Registro: {{ $profissional->cro ?: '-' }}</div>
                <div>Comissão base: {{ $cp?->comissao ? $cp->comissao.'%' : '-' }}</div>
                <div>Admissão: {{ $profissional->created_at?->format('d/m/Y') }}</div>
            </div>
        </div>
        <div class="bg-white rounded shadow p-4 space-y-2">
            <h3 class="font-semibold mb-2">Contato</h3>
            <div>Email: {{ $profissional->email }}</div>
            <div>Telefone: {{ $profissional->phone ?: '-' }}</div>
            <div class="text-sm text-gray-700">Endereço: {{ trim($profissional->logradouro.' '.$profissional->numero.' '.$profissional->complemento) }} - {{ $profissional->bairro }}, {{ $profissional->cidade }} - {{ $profissional->estado }}, {{ $profissional->cep }}</div>
        </div>
        <div class="bg-white rounded shadow p-4 space-y-2">
            <div class="flex justify-between items-center mb-2">
                <h3 class="font-semibold">Clínicas</h3>
            </div>
            @php
                $clinicasHorario = $profissional->horariosProfissional
                    ->map(fn($h) => $h->clinic)
                    ->filter()
                    ->unique('id');
            @endphp
            <div class="space-y-2">
                @foreach($clinicasHorario as $clinica)
                    <div class="flex justify-between items-center">
                        <div>
                            <span class="font-medium">{{ $clinica->nome }}</span>
                            @if($loop->first)
                                <span class="ml-1 text-xs px-2 py-0.5 rounded bg-blue-100 text-blue-700">Principal</span>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
    <div class="bg-white rounded shadow p-4 lg:col-span-2" x-data="{ tab: 'dados' }">
        <div class="mb-4 border-b flex gap-4">
            <button type="button" @click="tab='dados'" :class="tab==='dados' ? 'border-b-2 border-blue-600' : ''" class="pb-2">Dados pessoais</button>
            <button type="button" @click="tab='profissionais'" :class="tab==='profissionais' ? 'border-b-2 border-blue-600' : ''" class="pb-2">Dados Profissionais</button>
            <button type="button" @click="tab='contato'" :class="tab==='contato' ? 'border-b-2 border-blue-600' : ''" class="pb-2">Contato</button>
            <button type="button" @click="tab='comissoes'" :class="tab==='comissoes' ? 'border-b-2 border-blue-600' : ''" class="pb-2">Comissões</button>
            <button type="button" @click="tab='horarios'" :class="tab==='horarios' ? 'border-b-2 border-blue-600' : ''" class="pb-2">Horário de trabalho</button>
        </div>
        <div x-show="tab==='dados'" class="space-y-4">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm">
                <div><span class="font-medium">Nome</span><p>{{ $profissional->first_name }}</p></div>
                <div><span class="font-medium">Nome do meio</span><p>{{ $profissional->middle_name ?: '-' }}</p></div>
                <div><span class="font-medium">Último nome</span><p>{{ $profissional->last_name }}</p></div>
                <div><span class="font-medium">CPF</span><p>{{ $profissional->cpf ?: '-' }}</p></div>
                <div class="sm:col-span-2">
                    <span class="font-medium block">Foto</span>
                    <img src="{{ $profissional->photo_path ? asset('storage/'.$profissional->photo_path) : 'https://via.placeholder.com/80' }}" class="w-20 h-20 rounded-full object-cover mt-1" alt="Foto">
                </div>
            </div>
        </div>
        <div x-show="tab==='profissionais'" class="space-y-4" x-cloak>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm">
                <div class="sm:col-span-2"><span class="font-medium">Cargo</span><p>{{ $profissional->cargo ?: '-' }}</p></div>
                <div><span class="font-medium">Dentista</span><p>{{ $profissional->dentista ? 'Sim' : 'Não' }}</p></div>
                @if($profissional->dentista)
                    <div><span class="font-medium">CRO</span><p>{{ $profissional->cro ?: '-' }}</p></div>
                    <div><span class="font-medium">Especialidade</span><p>{{ $profissional->especialidade ?: '-' }}</p></div>
                @endif
            </div>
        </div>
        <div x-show="tab==='contato'" class="space-y-4" x-cloak>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm">
                <div><span class="font-medium">Email</span><p>{{ $profissional->email }}</p></div>
                <div><span class="font-medium">Telefone</span><p>{{ $profissional->phone ?: '-' }}</p></div>
                <div><span class="font-medium">CEP</span><p>{{ $profissional->cep ?: '-' }}</p></div>
                <div class="sm:col-span-2"><span class="font-medium">Logradouro</span><p>{{ $profissional->logradouro ?: '-' }}</p></div>
                <div><span class="font-medium">Número</span><p>{{ $profissional->numero ?: '-' }}</p></div>
                <div><span class="font-medium">Complemento</span><p>{{ $profissional->complemento ?: '-' }}</p></div>
                <div><span class="font-medium">Bairro</span><p>{{ $profissional->bairro ?: '-' }}</p></div>
                <div><span class="font-medium">Cidade</span><p>{{ $profissional->cidade ?: '-' }}</p></div>
                <div><span class="font-medium">Estado</span><p>{{ $profissional->estado ?: '-' }}</p></div>
            </div>
        </div>
        <div x-show="tab==='comissoes'" class="space-y-4" x-cloak>
            @foreach($profissional->clinicasProfissional as $clinica)
                <div class="border rounded p-2 text-sm flex justify-between">
                    <div>
                        <span class="font-medium">{{ $clinica->clinic->nome }}</span>
                        <span class="ml-1 text-xs">({{ $clinica->status }})</span>
                    </div>
                    <span>{{ $clinica->comissao ? $clinica->comissao.'%' : '-' }}</span>
                </div>
            @endforeach
        </div>
        <div x-show="tab==='horarios'" class="space-y-4" x-cloak>
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
            @foreach($profissional->clinicasProfissional as $clinica)
                <div class="border rounded p-2 text-sm">
                    <h4 class="font-medium mb-2">{{ $clinica->clinic->nome }}</h4>
                    @php $hs = $profissional->horariosProfissional->where('clinica_id', $clinica->clinica_id)->keyBy('dia_semana'); @endphp
                    <table class="w-full text-sm">
                        <thead>
                            <tr>
                                <th class="text-left p-1">Dia</th>
                                <th class="text-left p-1">Início</th>
                                <th class="text-left p-1">Fim</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($diasSemana as $diaKey => $diaLabel)
                                @php $h = $hs->get($diaKey); @endphp
                                <tr>
                                    <td class="p-1">{{ $diaLabel }}</td>
                                    <td class="p-1">{{ $h ? substr($h->hora_inicio, 0, 5) : '-' }}</td>
                                    <td class="p-1">{{ $h ? substr($h->hora_fim, 0, 5) : '-' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endforeach
        </div>
    </div>
</div>
@endsection
