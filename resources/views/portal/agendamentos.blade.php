@extends('layouts.app')

@section('content')
@include('partials.breadcrumbs', ['crumbs' => [ ['label' => 'Portal', 'url' => route('portal.index')], ['label' => 'Agendamentos'] ]])
@php
    use Illuminate\Support\Carbon;
    $months = [1=>'Janeiro',2=>'Fevereiro',3=>'Março',4=>'Abril',5=>'Maio',6=>'Junho',7=>'Julho',8=>'Agosto',9=>'Setembro',10=>'Outubro',11=>'Novembro',12=>'Dezembro'];
    $week = ['SEG','TER','QUA','QUI','SEX','SAB','DOM'];
    $start = Carbon::now()->startOfWeek(Carbon::MONDAY);
    $days = [];
    for($i=0;$i<7;$i++){
        $d = $start->copy()->addDays($i);
        $days[] = [
            'label'=>$week[$i],
            'number'=>$d->day,
            'month'=>$months[$d->month],
            'active'=>$d->isToday(),
            'past'=>$d->lt(Carbon::today()),
        ];
    }
    $professionals = [
        ['id'=>1,'name'=>'Dr. Raony'],
        ['id'=>2,'name'=>'Dra. Ana'],
        ['id'=>3,'name'=>'Dr. Pedro'],
    ];
    $horarios = [];
    $morningStart = Carbon::createFromTime(8, 0);
    $morningEnd = Carbon::createFromTime(11, 30);
    for ($time = $morningStart->copy(); $time <= $morningEnd; $time->addMinutes(30)) {
        $horarios[] = $time->format('H:i');
    }
    $afternoonStart = Carbon::createFromTime(14, 0);
    $afternoonEnd = Carbon::createFromTime(16, 30);
    for ($time = $afternoonStart->copy(); $time <= $afternoonEnd; $time->addMinutes(30)) {
        $horarios[] = $time->format('H:i');
    }
    $agenda = [
        1 => [
            '08:00' => ['paciente'=>'Maria','tipo'=>'Consulta','contato'=>'(11) 91234-5678','status'=>'confirmado'],
            '15:00' => ['paciente'=>'Raony','tipo'=>'Retorno','contato'=>'(11) 99876-5432','status'=>'cancelado'],
        ],
        2 => [
            '09:00' => ['paciente'=>'Ana','tipo'=>'Consulta','contato'=>'(11) 95555-4444','status'=>'confirmado'],
            '16:00' => ['paciente'=>'Luis','tipo'=>'Consulta','contato'=>'(11) 97777-2222','status'=>'vago'],
        ],
        3 => [
            '10:00' => ['paciente'=>'Pedro','tipo'=>'Consulta','contato'=>'(11) 94444-3333','status'=>'confirmado'],
        ],
    ];
@endphp
<div class="mb-6">
    <h1 class="text-2xl font-bold">Agendamentos</h1>
    <p class="text-gray-600">Agenda semanal por profissional</p>
</div>
<div class="flex items-center justify-between mb-4">
    <div class="flex items-center gap-2 flex-1">
        <button class="p-1 border rounded bg-white">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" /></svg>
        </button>
        <div class="flex gap-2 flex-1">
            @foreach($days as $day)
                <x-agenda.dia :label="$day['label']" :numero="$day['number']" :mes="$day['month']" :active="$day['active']" :past="$day['past']" />
            @endforeach
        </div>
        <button class="p-1 border rounded bg-white">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
        </button>
    </div>
    <div class="relative">
        <button class="p-2 border rounded bg-white">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
        </button>
        <span class="absolute -top-1 -right-1 bg-red-600 text-white text-[10px] rounded-full px-1">23</span>
    </div>
</div>
<div class="flex items-center gap-2 overflow-x-auto mb-4">
    <x-agenda.profissional name="Todos os Profissionais" active />
    @foreach($professionals as $prof)
        <x-agenda.profissional :name="$prof['name']" />
    @endforeach
</div>
<div class="flex space-x-6 border-b mb-4 text-sm">
    <button class="pb-2 border-b-2 border-primary text-primary">Por Consultório, Raony</button>
    <button class="pb-2 text-gray-600">Fila de Espera</button>
    <button class="pb-2 text-gray-600">Filtrar</button>
</div>
<div class="overflow-auto">
    <table class="min-w-full text-sm">
        <thead>
            <tr>
                <th class="p-2 bg-gray-50 w-20"></th>
                @foreach($professionals as $prof)
                    <th class="p-2 bg-gray-50 text-left whitespace-nowrap">{{ $prof['name'] }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @foreach($horarios as $hora)
                <tr class="border-t">
                    <td class="bg-gray-50 w-20"><x-agenda.horario :time="$hora"/></td>
                    @foreach($professionals as $prof)
                        <td class="w-40 h-16">
                            @isset($agenda[$prof['id']][$hora])
                                @php $item = $agenda[$prof['id']][$hora]; @endphp
                                <x-agenda.agendamento :paciente="$item['paciente']" :tipo="$item['tipo']" :contato="$item['contato']" :status="$item['status']" />
                            @endisset
                        </td>
                    @endforeach
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
