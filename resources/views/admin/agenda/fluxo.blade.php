@extends('layouts.app')

@section('content')
@include('partials.breadcrumbs', ['crumbs' => [
    ['label' => 'Dashboard', 'url' => route('admin.index')],
    ['label' => 'Agenda']
]])
<div class="mb-6 flex items-start justify-between">
    <div>
        <h1 class="text-2xl font-bold">Agenda</h1>
        <p class="text-gray-600">Gerenciamento de consultas e horários</p>
    </div>
    <div class="flex items-center gap-2">
        <button class="p-2 text-gray-600 hover:text-gray-800">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6 6 0 10-12 0v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
            </svg>
        </button>
        <a href="{{ route('agendamentos.index') }}" class="py-2 px-4 bg-blue-600 text-white rounded hover:bg-blue-700 flex items-center">+ Nova Consulta</a>
    </div>
</div>
<div class="border-b mb-6">
    <nav class="-mb-px flex gap-4">
        <a href="{{ route('agenda.index') }}" class="px-1 pb-2 text-gray-500 hover:text-gray-700">Agenda</a>
        <a href="#" class="px-1 pb-2 border-b-2 border-blue-600 text-blue-600">Fluxo</a>
    </nav>
</div>
<div x-data="agendaFluxo()" x-init="setup()">
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Clínica</label>
            <select x-model="filters.clinicId" class="form-select block w-full rounded-md border border-gray-300 bg-gray-50 px-3 py-2 text-sm text-gray-700 focus:border-primary focus:ring-primary">
                <option value="all">Todas</option>
                <template x-for="c in clinics" :key="c.id">
                    <option :value="c.id" x-text="c.name"></option>
                </template>
            </select>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Profissional</label>
            <select x-model="filters.professionalId" class="form-select block w-full rounded-md border border-gray-300 bg-gray-50 px-3 py-2 text-sm text-gray-700 focus:border-primary focus:ring-primary">
                <option value="all">Todos</option>
                <template x-for="p in professionals" :key="p.id">
                    <option :value="p.id" x-text="p.name"></option>
                </template>
            </select>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Período</label>
            <select x-model="filters.period" class="form-select block w-full rounded-md border border-gray-300 bg-gray-50 px-3 py-2 text-sm text-gray-700 focus:border-primary focus:ring-primary">
                <option value="all">Dia todo</option>
                <option value="morning">Manhã</option>
                <option value="afternoon">Tarde</option>
            </select>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Data</label>
            <input type="date" x-model="filters.date" class="form-input block w-full rounded-md border border-gray-300 bg-gray-50 px-3 py-2 text-sm text-gray-700 focus:border-primary focus:ring-primary" />
        </div>
    </div>
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
        <div class="bg-white rounded-2xl shadow-sm p-6 flex items-center">
            <div class="p-2 rounded-full bg-yellow-100 text-yellow-600">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3" />
                </svg>
            </div>
            <div class="ml-4">
                <div class="text-2xl font-bold" x-text="filtered('waiting').length"></div>
                <div class="text-sm text-gray-500 mt-1">Em Espera</div>
            </div>
        </div>
        <div class="bg-white rounded-2xl shadow-sm p-6 flex items-center">
            <div class="p-2 rounded-full bg-green-100 text-green-600">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
            </div>
            <div class="ml-4">
                <div class="text-2xl font-bold" x-text="filtered('in_service').length"></div>
                <div class="text-sm text-gray-500 mt-1">Em Atendimento</div>
            </div>
        </div>
        <div class="bg-white rounded-2xl shadow-sm p-6 flex items-center">
            <div class="p-2 rounded-full bg-gray-100 text-gray-600">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5l14 14M5 19L19 5" />
                </svg>
            </div>
            <div class="ml-4">
                <div class="text-2xl font-bold" x-text="filtered('done').length"></div>
                <div class="text-sm text-gray-500 mt-1">Finalizados</div>
            </div>
        </div>
        <div class="bg-white rounded-2xl shadow-sm p-6 flex items-center">
            <div class="p-2 rounded-full bg-blue-100 text-blue-600">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3" />
                </svg>
            </div>
            <div class="ml-4">
                <div class="text-2xl font-bold">15min</div>
                <div class="text-sm text-gray-500 mt-1">Espera Média</div>
            </div>
        </div>
    </div>
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6">
        <template x-for="col in columnOrder" :key="col">
            <div class="bg-white rounded-2xl shadow-sm p-6 flex flex-col">
                <div class="mb-4">
                    <div class="flex items-center justify-between">
                        <span class="px-2 py-0.5 rounded-full text-xs font-medium" :class="columnInfo[col].badge" x-text="columnInfo[col].label"></span>
                        <span class="text-sm text-gray-500" x-text="filtered(col).length"></span>
                    </div>
                    <p class="text-sm text-gray-500 mt-2" x-text="columnInfo[col].desc"></p>
                </div>
                <div class="space-y-4 flex-1">
                    <template x-for="item in filtered(col)" :key="item.id">
                        <div class="bg-white border rounded-xl p-4 flex flex-col gap-2">
                            <div class="flex items-start">
                                <h4 class="font-semibold flex-1 truncate" x-text="item.patient"></h4>
                                <button class="text-gray-400">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6h.01M12 12h.01M12 18h.01" />
                                    </svg>
                                </button>
                            </div>
                            <div class="flex items-center text-sm text-gray-600">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3" />
                                </svg>
                                <span x-text="item.time"></span>
                                <span class="ml-auto px-2 py-0.5 rounded text-xs" :class="priorityClass(item.priority)" x-text="item.priority"></span>
                            </div>
                            <div class="flex items-center text-sm text-gray-600">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A13.937 13.937 0 0112 15c2.389 0 4.628.557 6.879 1.804M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                <span class="truncate" x-text="item.professional"></span>
                            </div>
                            <div class="text-sm text-gray-600 truncate" x-text="item.reason"></div>
                            <div class="flex justify-between text-sm text-gray-500">
                                <span x-text="timeInStatus(item)"></span>
                                <template x-if="col === 'in_service'">
                                    <span x-text="'Cadeira ' + item.chair"></span>
                                </template>
                            </div>
                            <div class="mt-4">
                                <template x-if="col === 'check_in'">
                                    <button @click="move(item, 'check_in', 'waiting')" class="w-full bg-blue-600 text-white py-2 rounded-lg hover:bg-blue-700">Mover para Espera</button>
                                </template>
                                <template x-if="col === 'waiting'">
                                    <button @click="move(item, 'waiting', 'in_service')" class="w-full bg-blue-600 text-white py-2 rounded-lg hover:bg-blue-700">Iniciar Atendimento</button>
                                </template>
                                <template x-if="col === 'in_service'">
                                    <div class="flex gap-2">
                                        <button @click="move(item, 'in_service', 'done')" class="flex-1 bg-blue-600 text-white py-2 rounded-lg hover:bg-blue-700">Finalizar Atendimento</button>
                                        <button @click="move(item, 'in_service', 'waiting')" class="p-2 rounded-lg border text-gray-600 hover:bg-gray-50" title="Voltar à Espera">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h4l3 3m0 0l3-3h4" />
                                            </svg>
                                        </button>
                                    </div>
                                </template>
                            </div>
                        </div>
                    </template>
                </div>
            </div>
        </template>
    </div>
</div>
<script>
function agendaFluxo() {
    const today = new Date().toISOString().split('T')[0];
    return {
        filters: { clinicId: 'all', professionalId: 'all', period: 'all', date: today },
        clinics: [
            { id: '1', name: 'Clínica A' },
            { id: '2', name: 'Clínica B' }
        ],
        professionals: [
            { id: '1', name: 'Dr. João' },
            { id: '2', name: 'Dra. Maria' }
        ],
        columnOrder: ['check_in', 'waiting', 'in_service', 'done'],
        columnInfo: {
            check_in: { label: 'Check-in', desc: 'Pacientes que chegaram', badge: 'bg-blue-100 text-blue-600' },
            waiting: { label: 'Em Espera', desc: 'Aguardando atendimento', badge: 'bg-yellow-100 text-yellow-600' },
            in_service: { label: 'Em Atendimento', desc: 'Sendo atendidos', badge: 'bg-green-100 text-green-600' },
            done: { label: 'Finalizado', desc: 'Atendimentos concluídos', badge: 'bg-gray-100 text-gray-600' },
        },
        columns: {
            check_in: [
                { id: 1, patient: 'Maria Silva', time: '09:00', professional: 'Dr. João', professionalId: '1', clinicId: '1', period: 'morning', reason: 'Consulta', priority: 'Alta', enteredAt: Date.now() - 5*60000, statusChangedAt: Date.now() - 5*60000 },
                { id: 2, patient: 'Pedro Santos', time: '09:30', professional: 'Dra. Maria', professionalId: '2', clinicId: '2', period: 'morning', reason: 'Retorno', priority: 'Média', enteredAt: Date.now() - 10*60000, statusChangedAt: Date.now() - 10*60000 },
            ],
            waiting: [
                { id: 3, patient: 'Carlos Oliveira', time: '10:00', professional: 'Dr. João', professionalId: '1', clinicId: '1', period: 'morning', reason: 'Emergência', priority: 'Alta', enteredAt: Date.now() - 20*60000, statusChangedAt: Date.now() - 15*60000 },
            ],
            in_service: [
                { id: 4, patient: 'Ana Costa', time: '10:30', professional: 'Dra. Maria', professionalId: '2', clinicId: '2', period: 'morning', reason: 'Consulta', priority: 'Baixa', chair: '2', enteredAt: Date.now() - 40*60000, statusChangedAt: Date.now() - 5*60000 },
            ],
            done: [
                { id: 5, patient: 'João Pedro', time: '08:00', professional: 'Dr. João', professionalId: '1', clinicId: '1', period: 'morning', reason: 'Retorno', priority: 'Média', enteredAt: Date.now() - 120*60000, statusChangedAt: Date.now() - 30*60000 },
            ],
        },
        now: Date.now(),
        setup() {
            const t = setInterval(() => { this.now = Date.now(); }, 60000);
            return () => clearInterval(t);
        },
        filtered(col) {
            return this.columns[col].filter(item => {
                if (this.filters.clinicId !== 'all' && item.clinicId !== this.filters.clinicId) return false;
                if (this.filters.professionalId !== 'all' && item.professionalId !== this.filters.professionalId) return false;
                if (this.filters.period !== 'all' && item.period !== this.filters.period) return false;
                if (this.filters.date && new Date(item.enteredAt).toISOString().slice(0,10) !== this.filters.date) return false;
                return true;
            });
        },
        move(item, from, to) {
            const idx = this.columns[from].indexOf(item);
            if (idx >= 0) {
                this.columns[from].splice(idx, 1);
                item.statusChangedAt = Date.now();
                if (to === 'in_service' && !item.chair) item.chair = String(Math.ceil(Math.random() * 3));
                if (to !== 'in_service') delete item.chair;
                this.columns[to].push(item);
            }
        },
        timeInStatus(item) {
            const diff = Math.floor((this.now - item.statusChangedAt) / 60000);
            return diff + 'min';
        },
        priorityClass(p) {
            return {
                'Alta': 'bg-red-100 text-red-600',
                'Média': 'bg-yellow-100 text-yellow-600',
                'Baixa': 'bg-green-100 text-green-600',
            }[p] || '';
        }
    }
}
</script>
@endsection
