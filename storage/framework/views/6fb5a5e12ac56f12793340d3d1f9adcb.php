

<?php $__env->startSection('content'); ?>
<div class="flex items-start justify-between mb-6">
    <div>
        <h1 class="text-2xl font-bold">Dashboard</h1>
        <p class="text-gray-600">Visão geral de todas as unidades</p>
    </div>
    <div class="flex items-center space-x-2">
        <?php if (isset($component)) { $__componentOriginalccc14dc8ed8a5ec671c356860d074e0d = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalccc14dc8ed8a5ec671c356860d074e0d = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.dashboard.select-unidade','data' => ['options' => ['Centro', 'Norte', 'Sul']]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('dashboard.select-unidade'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['options' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(['Centro', 'Norte', 'Sul'])]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalccc14dc8ed8a5ec671c356860d074e0d)): ?>
<?php $attributes = $__attributesOriginalccc14dc8ed8a5ec671c356860d074e0d; ?>
<?php unset($__attributesOriginalccc14dc8ed8a5ec671c356860d074e0d); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalccc14dc8ed8a5ec671c356860d074e0d)): ?>
<?php $component = $__componentOriginalccc14dc8ed8a5ec671c356860d074e0d; ?>
<?php unset($__componentOriginalccc14dc8ed8a5ec671c356860d074e0d); ?>
<?php endif; ?>
        <a href="#" class="py-2 px-4 bg-primary text-white rounded hover:bg-primary/90 text-sm">+ Novo Profissional</a>
    </div>
</div>
<div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4 mb-6">
    <div class="rounded-sm border border-stroke bg-white p-5 shadow">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500">Total de Pacientes</p>
                <p class="mt-1 text-xl font-bold text-black">350</p>
            </div>
            <div class="flex h-11 w-11 items-center justify-center rounded-full bg-primary/10 text-primary">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A13.937 13.937 0 0112 15c2.33 0 4.5.533 6.879 1.532M15 11a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
            </div>
        </div>
        <p class="mt-2 text-xs text-gray-500">+15% em relação ao mês anterior</p>
    </div>
    <div class="rounded-sm border border-stroke bg-white p-5 shadow">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500">Consultas Hoje</p>
                <p class="mt-1 text-xl font-bold text-black">28</p>
            </div>
            <div class="flex h-11 w-11 items-center justify-center rounded-full bg-primary/10 text-primary">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
            </div>
        </div>
        <p class="mt-2 text-xs text-gray-500">+5% em relação ao mês anterior</p>
    </div>
    <div class="rounded-sm border border-stroke bg-white p-5 shadow">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500">Cancelamentos Hoje</p>
                <p class="mt-1 text-xl font-bold text-black">3</p>
            </div>
            <div class="flex h-11 w-11 items-center justify-center rounded-full bg-primary/10 text-primary">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a4 4 0 00-4-4h-1M9 20H4v-2a4 4 0 014-4h1m4-8a4 4 0 110 8 4 4 0 010-8z" /></svg>
            </div>
        </div>
        <p class="mt-2 text-xs text-gray-500">+2 em relação ao mês anterior</p>
    </div>
    <div class="rounded-sm border border-stroke bg-white p-5 shadow">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500">Faturamento Diário</p>
                <p class="mt-1 text-xl font-bold text-black">R$ 12.000</p>
            </div>
            <div class="flex h-11 w-11 items-center justify-center rounded-full bg-primary/10 text-primary">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 1.343-3 3s1.343 3 3 3 3 1.343 3 3-1.343 3-3 3m0-12V4m0 16v-4m0 4c1.657 0 3-1.343 3-3s-1.343-3-3-3-3-1.343-3-3 1.343-3 3-3" /></svg>
            </div>
        </div>
        <p class="mt-2 text-xs text-gray-500">+10% em relação ao mês anterior</p>
    </div>
</div>
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
    <?php if (isset($component)) { $__componentOriginal1556d229f12c288214282e1286830d56 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal1556d229f12c288214282e1286830d56 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.dashboard.chart','data' => ['title' => 'Consultas do Dia']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('dashboard.chart'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Consultas do Dia']); ?>
        <ul class="mt-4 space-y-1 text-sm">
            <li class="flex justify-between"><span>Agendadas</span><span>20</span></li>
            <li class="flex justify-between"><span>Confirmadas</span><span>15</span></li>
            <li class="flex justify-between"><span>Canceladas</span><span>3</span></li>
            <li class="flex justify-between"><span>Realizadas</span><span>12</span></li>
        </ul>
     <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal1556d229f12c288214282e1286830d56)): ?>
<?php $attributes = $__attributesOriginal1556d229f12c288214282e1286830d56; ?>
<?php unset($__attributesOriginal1556d229f12c288214282e1286830d56); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal1556d229f12c288214282e1286830d56)): ?>
<?php $component = $__componentOriginal1556d229f12c288214282e1286830d56; ?>
<?php unset($__componentOriginal1556d229f12c288214282e1286830d56); ?>
<?php endif; ?>
    <?php if (isset($component)) { $__componentOriginal1556d229f12c288214282e1286830d56 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal1556d229f12c288214282e1286830d56 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.dashboard.chart','data' => ['title' => 'Ocupação Semanal']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('dashboard.chart'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Ocupação Semanal']); ?>
         <?php $__env->slot('header', null, []); ?> 
            <span class="text-sm text-gray-500">Taxa de ocupação atual: 78%</span>
         <?php $__env->endSlot(); ?>
     <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal1556d229f12c288214282e1286830d56)): ?>
<?php $attributes = $__attributesOriginal1556d229f12c288214282e1286830d56; ?>
<?php unset($__attributesOriginal1556d229f12c288214282e1286830d56); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal1556d229f12c288214282e1286830d56)): ?>
<?php $component = $__componentOriginal1556d229f12c288214282e1286830d56; ?>
<?php unset($__componentOriginal1556d229f12c288214282e1286830d56); ?>
<?php endif; ?>
</div>
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <?php if (isset($component)) { $__componentOriginal1556d229f12c288214282e1286830d56 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal1556d229f12c288214282e1286830d56 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.dashboard.chart','data' => ['title' => 'Taxa de Cancelamentos e Faltas (30 dias)']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('dashboard.chart'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Taxa de Cancelamentos e Faltas (30 dias)']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal1556d229f12c288214282e1286830d56)): ?>
<?php $attributes = $__attributesOriginal1556d229f12c288214282e1286830d56; ?>
<?php unset($__attributesOriginal1556d229f12c288214282e1286830d56); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal1556d229f12c288214282e1286830d56)): ?>
<?php $component = $__componentOriginal1556d229f12c288214282e1286830d56; ?>
<?php unset($__componentOriginal1556d229f12c288214282e1286830d56); ?>
<?php endif; ?>
    <?php if (isset($component)) { $__componentOriginal1556d229f12c288214282e1286830d56 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal1556d229f12c288214282e1286830d56 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.dashboard.chart','data' => ['title' => 'Principais Procedimentos']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('dashboard.chart'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Principais Procedimentos']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal1556d229f12c288214282e1286830d56)): ?>
<?php $attributes = $__attributesOriginal1556d229f12c288214282e1286830d56; ?>
<?php unset($__attributesOriginal1556d229f12c288214282e1286830d56); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal1556d229f12c288214282e1286830d56)): ?>
<?php $component = $__componentOriginal1556d229f12c288214282e1286830d56; ?>
<?php unset($__componentOriginal1556d229f12c288214282e1286830d56); ?>
<?php endif; ?>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\dentix\resources\views/dashboard.blade.php ENDPATH**/ ?>