<div x-ignore id="schedule-modal" data-hora="" data-date="" class="fixed inset-0 bg-black/50 hidden flex items-center justify-center z-50">
    <div class="bg-white rounded p-4 w-[32rem]">
        <div id="step-1">
            <h2 class="text-lg font-semibold mb-2">Buscar Paciente</h2>
            <div class="flex gap-2">
                <input id="patient-search" type="text" data-search-url="{{ route('pacientes.search') }}" placeholder="Nome, e-mail, CPF ou telefone" class="mt-1 w-full border rounded p-2 flex-1" />
                <button id="patient-search-btn" type="button" class="mt-1 px-3 py-2 bg-primary text-white rounded">Pesquisar</button>
            </div>
            <ul id="patient-results" class="mt-2 max-h-60 overflow-auto border rounded"></ul>
            <div id="patient-notfound" class="mt-2 text-sm text-red-600 hidden">Paciente não encontrado</div>
        </div>
        <div id="step-2" class="hidden">
            <h2 class="text-lg font-semibold mb-2">Confirmar Agendamento</h2>
            <p id="schedule-summary" class="text-sm text-gray-600 mb-4"></p>
            <p class="mb-4"><span class="font-medium">Paciente:</span> <span id="selected-patient-name"></span></p>
            <div class="flex gap-2 mb-4">
                <label class="block flex-1">
                    <span class="text-sm">Início</span>
                    <input id="schedule-start" type="time" step="900" class="mt-1 w-full border rounded p-1" />
                </label>
                <label class="block flex-1">
                    <span class="text-sm">Fim</span>
                    <input id="schedule-end" type="time" step="900" class="mt-1 w-full border rounded p-1" />
                </label>
            </div>
            <input type="hidden" id="hora_inicio" name="hora_inicio">
            <input type="hidden" id="hora_fim" name="hora_fim">
            <input type="hidden" id="schedule-professional">
            <input type="hidden" id="schedule-date">
            <input type="hidden" id="schedule-paciente">
            <input type="hidden" id="agendamento-id">
            <label class="block mb-4">
                <span class="text-sm">Observação</span>
                <textarea id="schedule-observacao" class="mt-1 w-full border rounded p-1" placeholder="Digite aqui"></textarea>
            </label>
            <label class="block mb-4">
                <span class="text-sm">Status</span>
                <select id="schedule-status" class="mt-1 w-full border rounded p-1">
                    <option value="confirmado">Confirmado</option>
                    <option value="pendente">Pendente</option>
                    <option value="cancelado">Cancelado</option>
                    <option value="faltou">Faltou</option>
                </select>
            </label>
            <div class="flex justify-end gap-2">
                <button id="schedule-cancel" class="px-3 py-1 border rounded">Cancelar</button>
                <button id="schedule-save" data-store-url="{{ route('agendamentos.store') }}" data-update-url="{{ url('admin/agendamentos') }}" class="px-3 py-1 bg-primary text-white rounded" disabled>Salvar</button>
            </div>
        </div>
    </div>
</div>
