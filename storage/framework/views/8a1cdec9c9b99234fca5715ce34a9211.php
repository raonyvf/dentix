

<?php $__env->startSection('content'); ?>
<?php echo $__env->make('partials.breadcrumbs', ['crumbs' => [
    ['label' => 'Dashboard', 'url' => route('admin.index')],
    ['label' => 'Agenda', 'url' => route('agenda.index')],
    ['label' => 'Novo Agendamento']
]], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<div class="mb-6">
    <h1 class="text-2xl font-bold">Agendamentos</h1>
    <p class="text-gray-600">Agenda semanal por profissional</p>
</div>
<div id="schedule-success" class="hidden mb-4 rounded bg-green-500 text-white px-4 py-2"></div>
<?php
    // Dados de agenda são fornecidos pelo controlador
?>
<div x-data="agendaCalendar()" x-init="init()" data-horarios-url="<?php echo e(route('agendamentos.horarios')); ?>" data-professionals-url="<?php echo e(route('agendamentos.professionals')); ?>" data-base-times='<?php echo json_encode($horarios, 15, 512) ?>' data-current-date="<?php echo e($date); ?>">
    <div class="flex justify-end mb-2 relative">
        <button @click="openDatePicker" class="p-2 border rounded bg-white">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
            </svg>
        </button>
        <input x-ref="picker" type="date" class="hidden" @change="onDateSelected($event.target.value)" />
        <span class="absolute -top-1 -right-1 bg-red-600 text-white text-[10px] rounded-full px-1">23</span>
    </div>
    <div class="flex items-center justify-between mb-4">
        <div class="flex items-center gap-2 flex-1">
            <button @click="prevWeek" class="p-1 border rounded bg-white">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
            </button>
            <div class="flex gap-2 flex-1">
                <template x-for="day in days" :key="day.date">
                    <div :class="day.classes" @click="selectDay(day.date)">
                        <span class="uppercase" x-text="day.label"></span>
                        <span class="font-semibold" x-text="day.number"></span>
                        <span x-text="day.month"></span>
                    </div>
                </template>
            </div>
            <button @click="nextWeek" class="p-1 border rounded bg-white">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
            </button>
        </div>
    </div>
<div id="professionals-bar" class="flex items-center gap-2 overflow-x-auto mb-4">
    <?php if (isset($component)) { $__componentOriginal75d53c42762549e9d1c1fd8c0a2665ed = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal75d53c42762549e9d1c1fd8c0a2665ed = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.agenda.profissional','data' => ['name' => 'Todos os Profissionais','active' => true]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('agenda.profissional'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'Todos os Profissionais','active' => true]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal75d53c42762549e9d1c1fd8c0a2665ed)): ?>
<?php $attributes = $__attributesOriginal75d53c42762549e9d1c1fd8c0a2665ed; ?>
<?php unset($__attributesOriginal75d53c42762549e9d1c1fd8c0a2665ed); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal75d53c42762549e9d1c1fd8c0a2665ed)): ?>
<?php $component = $__componentOriginal75d53c42762549e9d1c1fd8c0a2665ed; ?>
<?php unset($__componentOriginal75d53c42762549e9d1c1fd8c0a2665ed); ?>
<?php endif; ?>
    <?php $__currentLoopData = $professionals; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $prof): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <?php if (isset($component)) { $__componentOriginal75d53c42762549e9d1c1fd8c0a2665ed = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal75d53c42762549e9d1c1fd8c0a2665ed = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.agenda.profissional','data' => ['name' => $prof['name']]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('agenda.profissional'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($prof['name'])]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal75d53c42762549e9d1c1fd8c0a2665ed)): ?>
<?php $attributes = $__attributesOriginal75d53c42762549e9d1c1fd8c0a2665ed; ?>
<?php unset($__attributesOriginal75d53c42762549e9d1c1fd8c0a2665ed); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal75d53c42762549e9d1c1fd8c0a2665ed)): ?>
<?php $component = $__componentOriginal75d53c42762549e9d1c1fd8c0a2665ed; ?>
<?php unset($__componentOriginal75d53c42762549e9d1c1fd8c0a2665ed); ?>
<?php endif; ?>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</div>
<div class="flex space-x-6 border-b mb-4 text-sm">
    <button class="pb-2 border-b-2 border-primary text-primary">Por Consultório, Raony</button>
    <button class="pb-2 text-gray-600">Fila de Espera</button>
    <button class="pb-2 text-gray-600">Filtrar</button>
</div>
<div class="overflow-auto" id="schedule-container">
    <div id="schedule-closed" class="hidden text-center py-4 text-gray-500">A clínica está fechada neste dia.</div>
    <div id="schedule-empty" class="hidden text-center py-4 text-gray-500">Sem profissionais disponíveis neste dia.</div>
    <table id="schedule-table" class="min-w-full text-sm table-fixed">
        <thead>
            <tr>
                <th class="p-2 bg-gray-50 w-24 min-w-[6rem]"></th>
                <?php $__currentLoopData = $professionals; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $prof): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <th class="p-2 bg-gray-50 text-left whitespace-nowrap border-l" data-professional-id="<?php echo e($prof['id']); ?>"><?php echo e($prof['name']); ?></th>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tr>
        </thead>
        <tbody>
            <?php $__currentLoopData = $horarios; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $hora): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr class="border-t" data-row="<?php echo e($hora); ?>">
                    <td class="bg-gray-50 w-24 min-w-[6rem] h-16 align-middle" data-slot="<?php echo e($hora); ?>" data-hora="<?php echo e($hora); ?>"><?php if (isset($component)) { $__componentOriginalc89f05dcf441308ceea92302048055de = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalc89f05dcf441308ceea92302048055de = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.agenda.horario','data' => ['time' => $hora]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('agenda.horario'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['time' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($hora)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalc89f05dcf441308ceea92302048055de)): ?>
<?php $attributes = $__attributesOriginalc89f05dcf441308ceea92302048055de; ?>
<?php unset($__attributesOriginalc89f05dcf441308ceea92302048055de); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc89f05dcf441308ceea92302048055de)): ?>
<?php $component = $__componentOriginalc89f05dcf441308ceea92302048055de; ?>
<?php unset($__componentOriginalc89f05dcf441308ceea92302048055de); ?>
<?php endif; ?></td>
                    <?php $__currentLoopData = $professionals; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $prof): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php $item = $agenda[$prof['id']][$hora] ?? null; ?>
                        <?php if($item && ($item['skip'] ?? false)): ?>
                            <?php continue; ?>
                        <?php endif; ?>
                        <td class="h-16 cursor-pointer border-l" data-professional-id="<?php echo e($prof['id']); ?>" data-hora="<?php echo e($hora); ?>" data-date="<?php echo e($date); ?>" <?php if($item && isset($item['rowspan'])): ?> rowspan="<?php echo e($item['rowspan']); ?>" <?php endif; ?>>
                            <?php if($item): ?>
                                <?php if (isset($component)) { $__componentOriginalafdbdac905d51ac4e2e12d4fadf83bb1 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalafdbdac905d51ac4e2e12d4fadf83bb1 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.agenda.agendamento','data' => ['paciente' => $item['paciente'],'observacao' => $item['observacao'],'status' => $item['status']]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('agenda.agendamento'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['paciente' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($item['paciente']),'observacao' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($item['observacao']),'status' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($item['status'])]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalafdbdac905d51ac4e2e12d4fadf83bb1)): ?>
<?php $attributes = $__attributesOriginalafdbdac905d51ac4e2e12d4fadf83bb1; ?>
<?php unset($__attributesOriginalafdbdac905d51ac4e2e12d4fadf83bb1); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalafdbdac905d51ac4e2e12d4fadf83bb1)): ?>
<?php $component = $__componentOriginalafdbdac905d51ac4e2e12d4fadf83bb1; ?>
<?php unset($__componentOriginalafdbdac905d51ac4e2e12d4fadf83bb1); ?>
<?php endif; ?>
                            <?php endif; ?>
                        </td>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </tbody>
    </table>
</div>
<div x-ignore id="schedule-modal" data-hora="" data-date="" class="fixed inset-0 bg-black/50 hidden flex items-center justify-center z-50">
    <div class="bg-white rounded p-4 w-[32rem]">
        <div id="step-1">
            <h2 class="text-lg font-semibold mb-2">Buscar Paciente</h2>
            <div class="flex gap-2">
                <input id="patient-search" type="text" data-search-url="<?php echo e(route('pacientes.search')); ?>" placeholder="Nome, e-mail, CPF ou telefone" class="mt-1 w-full border rounded p-2 flex-1" />
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
                    <input id="schedule-start" type="time" class="mt-1 w-full border rounded p-1" />
                </label>
                <label class="block flex-1">
                    <span class="text-sm">Fim</span>
                    <input id="schedule-end" type="time" class="mt-1 w-full border rounded p-1" />
                </label>
            </div>
            <input type="hidden" id="hora_inicio" name="hora_inicio">
            <input type="hidden" id="hora_fim" name="hora_fim">
            <input type="hidden" id="schedule-professional">
            <input type="hidden" id="schedule-date">
            <input type="hidden" id="schedule-paciente">
            <label class="block mb-4">
                <span class="text-sm">Observação</span>
                <textarea id="schedule-observacao" class="mt-1 w-full border rounded p-1" placeholder="Digite aqui"></textarea>
            </label>
            <div class="flex justify-end gap-2">
                <button id="schedule-cancel" class="px-3 py-1 border rounded">Cancelar</button>
                <button id="schedule-save" data-store-url="<?php echo e(route('agendamentos.store')); ?>" class="px-3 py-1 bg-primary text-white rounded" disabled>Salvar</button>
            </div>
        </div>
    </div>
</div>

<div id="date-picker-modal" class="fixed inset-0 bg-black/50 hidden flex items-center justify-center z-50">
    <div class="bg-white rounded p-4 w-80">
        <div class="mb-2 flex items-center justify-between">
            <button type="button" id="dp-prev" class="px-2">&#60;</button>
            <span id="dp-month-label" class="font-semibold"></span>
            <button type="button" id="dp-next" class="px-2">&#62;</button>
        </div>
        <div id="dp-calendar" class="mb-2 text-sm"></div>
    </div>
</div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\dentix\resources\views/agendamentos/index.blade.php ENDPATH**/ ?>