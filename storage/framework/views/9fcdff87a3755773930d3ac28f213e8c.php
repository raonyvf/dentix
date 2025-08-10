

<?php $__env->startSection('content'); ?>
<?php echo $__env->make('partials.breadcrumbs', ['crumbs' => [
    ['label' => 'Dashboard', 'url' => route('admin.index')],
    ['label' => 'Estoque e Suprimentos']
]], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<div class="flex items-start justify-between mb-6">
    <div>
        <h1 class="text-2xl font-bold">Estoque e Suprimentos</h1>
        <p class="text-gray-600">Gestão completa de materiais odontológicos e EPIs</p>
    </div>
    <div class="flex items-center gap-2">
        <?php if (isset($component)) { $__componentOriginalccc14dc8ed8a5ec671c356860d074e0d = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalccc14dc8ed8a5ec671c356860d074e0d = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.dashboard.select-unidade','data' => ['options' => $clinics]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('dashboard.select-unidade'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['options' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($clinics)]); ?>
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
        <a href="#" class="py-2 px-4 bg-emerald-600 text-white rounded hover:bg-emerald-700 text-sm">+ Novo Produto</a>
        <a href="#" class="py-2 px-4 border rounded text-sm">Pedidos</a>
        <a href="#" class="py-2 px-4 border rounded text-sm">Transferências</a>
        <a href="#" class="py-2 px-4 border rounded text-sm">Relatórios</a>
    </div>
</div>
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    <?php if (isset($component)) { $__componentOriginalc196470d5436dac6266616cef2a92302 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalc196470d5436dac6266616cef2a92302 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.dashboard.stats-card','data' => ['title' => 'Total de Itens','value' => $produtos->count().' itens • '.collect($produtos)->pluck('categoria')->unique()->count().' categorias']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('dashboard.stats-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Total de Itens','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($produtos->count().' itens • '.collect($produtos)->pluck('categoria')->unique()->count().' categorias')]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalc196470d5436dac6266616cef2a92302)): ?>
<?php $attributes = $__attributesOriginalc196470d5436dac6266616cef2a92302; ?>
<?php unset($__attributesOriginalc196470d5436dac6266616cef2a92302); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc196470d5436dac6266616cef2a92302)): ?>
<?php $component = $__componentOriginalc196470d5436dac6266616cef2a92302; ?>
<?php unset($__componentOriginalc196470d5436dac6266616cef2a92302); ?>
<?php endif; ?>
    <?php if (isset($component)) { $__componentOriginalc196470d5436dac6266616cef2a92302 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalc196470d5436dac6266616cef2a92302 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.dashboard.stats-card','data' => ['title' => 'Itens Críticos','value' => $itensCriticos->count()]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('dashboard.stats-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Itens Críticos','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($itensCriticos->count())]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalc196470d5436dac6266616cef2a92302)): ?>
<?php $attributes = $__attributesOriginalc196470d5436dac6266616cef2a92302; ?>
<?php unset($__attributesOriginalc196470d5436dac6266616cef2a92302); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc196470d5436dac6266616cef2a92302)): ?>
<?php $component = $__componentOriginalc196470d5436dac6266616cef2a92302; ?>
<?php unset($__componentOriginalc196470d5436dac6266616cef2a92302); ?>
<?php endif; ?>
    <?php if (isset($component)) { $__componentOriginalc196470d5436dac6266616cef2a92302 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalc196470d5436dac6266616cef2a92302 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.dashboard.stats-card','data' => ['title' => 'Valor do Estoque','value' => 'R$ '.number_format($valorEstoque,2,',','.'),'comparison' => 'Atualizado em '.$ultimaAtualizacao]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('dashboard.stats-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Valor do Estoque','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute('R$ '.number_format($valorEstoque,2,',','.')),'comparison' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute('Atualizado em '.$ultimaAtualizacao)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalc196470d5436dac6266616cef2a92302)): ?>
<?php $attributes = $__attributesOriginalc196470d5436dac6266616cef2a92302; ?>
<?php unset($__attributesOriginalc196470d5436dac6266616cef2a92302); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc196470d5436dac6266616cef2a92302)): ?>
<?php $component = $__componentOriginalc196470d5436dac6266616cef2a92302; ?>
<?php unset($__componentOriginalc196470d5436dac6266616cef2a92302); ?>
<?php endif; ?>
    <?php if (isset($component)) { $__componentOriginalc196470d5436dac6266616cef2a92302 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalc196470d5436dac6266616cef2a92302 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.dashboard.stats-card','data' => ['title' => 'Pedidos Pendentes','value' => random_int(0,10),'comparison' => 'Aguardando entrega']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('dashboard.stats-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Pedidos Pendentes','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(random_int(0,10)),'comparison' => 'Aguardando entrega']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalc196470d5436dac6266616cef2a92302)): ?>
<?php $attributes = $__attributesOriginalc196470d5436dac6266616cef2a92302; ?>
<?php unset($__attributesOriginalc196470d5436dac6266616cef2a92302); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc196470d5436dac6266616cef2a92302)): ?>
<?php $component = $__componentOriginalc196470d5436dac6266616cef2a92302; ?>
<?php unset($__componentOriginalc196470d5436dac6266616cef2a92302); ?>
<?php endif; ?>
</div>
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <div class="lg:col-span-2 space-y-6">
        <?php if (isset($component)) { $__componentOriginalc38fd2bb5c7e2f0648b923f6c4657760 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalc38fd2bb5c7e2f0648b923f6c4657760 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.dashboard.table','data' => ['title' => 'Produtos em Estoque','headings' => ['Produto','Categoria','Centro','Norte','Sul','Total','Estoque Mínimo','Status','Último Abastecimento','Ações']]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('dashboard.table'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Produtos em Estoque','headings' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(['Produto','Categoria','Centro','Norte','Sul','Total','Estoque Mínimo','Status','Último Abastecimento','Ações'])]); ?>
            <?php $__currentLoopData = $produtos->sortBy('nome'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr>
                    <td class="px-4 py-2 whitespace-nowrap"><?php echo e($p['nome']); ?></td>
                    <td class="px-4 py-2 whitespace-nowrap"><?php echo e($p['categoria']); ?></td>
                    <td class="px-4 py-2 text-center <?php echo e($p['centro'] < $p['minimo'] ? 'text-red-600' : ''); ?>"><?php echo e($p['centro']); ?></td>
                    <td class="px-4 py-2 text-center <?php echo e($p['norte'] < $p['minimo'] ? 'text-red-600' : ''); ?>"><?php echo e($p['norte']); ?></td>
                    <td class="px-4 py-2 text-center <?php echo e($p['sul'] < $p['minimo'] ? 'text-red-600' : ''); ?>"><?php echo e($p['sul']); ?></td>
                    <td class="px-4 py-2 text-center <?php echo e($p['total'] < $p['minimo'] ? 'text-red-600' : ''); ?>"><?php echo e($p['total']); ?></td>
                    <td class="px-4 py-2 text-center"><?php echo e($p['minimo']); ?></td>
                    <td class="px-4 py-2 text-center">
                        <span class="font-semibold <?php echo e($p['status']==='Baixo' ? 'text-red-600' : 'text-emerald-600'); ?>"><?php echo e($p['status']); ?></span>
                    </td>
                    <td class="px-4 py-2 text-center"><?php echo e($p['ultima_compra']); ?></td>
                    <td class="px-4 py-2 whitespace-nowrap">
                        <div class="flex items-center space-x-2">
                            <a href="#" class="text-gray-600 hover:text-blue-600" title="Detalhar">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                            </a>
                            <a href="#" class="text-gray-600 hover:text-blue-600" title="Editar">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m0 0a2.5 2.5 0 01-3.536 3.536L9 20.036l-4 1 1-4 6.232-6.232a2.5 2.5 0 013.536-3.536z" /></svg>
                            </a>
                            <button class="text-red-600 hover:text-red-800" title="Excluir">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6M1 7h22M10 7V4a1 1 0 011-1h2a1 1 0 011 1v3" /></svg>
                            </button>
                        </div>
                    </td>
                </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
         <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalc38fd2bb5c7e2f0648b923f6c4657760)): ?>
<?php $attributes = $__attributesOriginalc38fd2bb5c7e2f0648b923f6c4657760; ?>
<?php unset($__attributesOriginalc38fd2bb5c7e2f0648b923f6c4657760); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc38fd2bb5c7e2f0648b923f6c4657760)): ?>
<?php $component = $__componentOriginalc38fd2bb5c7e2f0648b923f6c4657760; ?>
<?php unset($__componentOriginalc38fd2bb5c7e2f0648b923f6c4657760); ?>
<?php endif; ?>
    </div>
    <div class="space-y-6">
        <div class="bg-white border border-red-200 rounded-lg p-4">
            <h3 class="text-lg font-semibold mb-2">Alertas de Estoque</h3>
            <ul class="space-y-2 text-sm">
                <?php $__empty_1 = true; $__currentLoopData = $itensCriticos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $c): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <li class="flex justify-between items-center">
                        <div>
                            <p class="font-medium"><?php echo e($c['nome']); ?> <span class="text-red-600"><?php echo e($c['total']); ?></span> / <?php echo e($c['minimo']); ?></p>
                            <p class="text-xs text-gray-500">Centro: <?php echo e($c['centro']); ?>, Norte: <?php echo e($c['norte']); ?>, Sul: <?php echo e($c['sul']); ?></p>
                        </div>
                        <a href="#" class="py-1 px-2 bg-blue-600 text-white rounded text-xs">Pedir</a>
                    </li>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <li class="text-sm text-gray-500">Sem alertas.</li>
                <?php endif; ?>
            </ul>
        </div>
        <div class="bg-white rounded-lg p-4 shadow">
            <h3 class="text-lg font-semibold mb-2">Consumo Recente</h3>
            <ul class="divide-y divide-gray-200 text-sm">
                <?php $__currentLoopData = $consumoRecente; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $c): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <li class="py-2 flex justify-between items-start">
                        <div>
                            <p class="font-medium"><?php echo e($c['nome']); ?></p>
                            <p class="text-xs text-gray-500">Centro: <?php echo e($c['centro']); ?>, Norte: <?php echo e($c['norte']); ?>, Sul: <?php echo e($c['sul']); ?></p>
                        </div>
                        <span class="text-xs text-gray-600"><?php echo e($c['responsavel']); ?></span>
                    </li>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </ul>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\dentix\resources\views/estoque/index.blade.php ENDPATH**/ ?>