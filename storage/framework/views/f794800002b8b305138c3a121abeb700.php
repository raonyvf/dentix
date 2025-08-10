

<?php $__env->startSection('content'); ?>
<?php echo $__env->make('partials.breadcrumbs', ['crumbs' => [
    ['label' => 'Dashboard', 'url' => route('admin.index')],
    ['label' => 'Profissionais']
]], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<div class="mb-6 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
    <div>
        <h1 class="text-2xl font-bold">Profissionais</h1>
        <p class="text-gray-600">Gestão de profissionais e funcionários</p>
    </div>
    <div class="flex items-center gap-2">
        <a href="<?php echo e(route('profissionais.create')); ?>" class="py-2 px-4 bg-blue-600 text-white rounded hover:bg-blue-700 text-sm">+ Novo Profissional</a>
        <div class="relative" x-data="{ open: false }">
            <button @click="open = !open" class="py-2 px-4 bg-white border rounded text-sm flex items-center gap-1">
                Relatórios
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                </svg>
            </button>
            <div x-show="open" @click.away="open = false" x-cloak class="absolute right-0 mt-2 w-40 bg-white border rounded shadow text-sm">
                <a href="#" class="block px-4 py-2 hover:bg-gray-100">Relatório 1</a>
                <a href="#" class="block px-4 py-2 hover:bg-gray-100">Relatório 2</a>
            </div>
        </div>
        <button class="py-2 px-4 bg-white border rounded text-sm flex items-center gap-1">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zm0 6a1 1 0 011-1h16a1 1 0 011 1v10a1 1 0 01-1 1H4a1 1 0 01-1-1V10z" />
            </svg>
            Mais Filtros
        </button>
    </div>
</div>

<div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
    <?php if (isset($component)) { $__componentOriginalc196470d5436dac6266616cef2a92302 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalc196470d5436dac6266616cef2a92302 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.dashboard.stats-card','data' => ['title' => 'Total de Profissionais','value' => $totalProfissionais,'comparison' => $dentistas.' Dentistas | '.$auxiliares.' Auxiliares']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('dashboard.stats-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute('Total de Profissionais'),'value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($totalProfissionais),'comparison' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($dentistas.' Dentistas | '.$auxiliares.' Auxiliares')]); ?>
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.dashboard.stats-card','data' => ['title' => 'Atendimentos (Último mês)','value' => '285','comparison' => '+12% em relação ao mês anterior']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('dashboard.stats-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Atendimentos (Último mês)','value' => '285','comparison' => '+12% em relação ao mês anterior']); ?>
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.dashboard.stats-card','data' => ['title' => 'Comissões (Total do mês)','value' => 'R$ 15.430,00','comparison' => 'Média de R$ 1.285,83 por profissional']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('dashboard.stats-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Comissões (Total do mês)','value' => 'R$ 15.430,00','comparison' => 'Média de R$ 1.285,83 por profissional']); ?>
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

<div class="mb-4">
    <label class="block text-sm font-medium text-gray-700 mb-1" for="clinica_id">Filtrar por Clínica</label>
    <select id="clinica_id" name="clinica_id" class="border-gray-300 rounded px-3 py-2 text-sm">
        <option value="">Todas as Clínicas</option>
        <?php $__currentLoopData = $clinicas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $clinica): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <option value="<?php echo e($clinica->id); ?>"><?php echo e($clinica->nome); ?></option>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </select>
</div>
<div class="bg-white rounded-lg shadow overflow-x-auto">
    <table class="min-w-full divide-y divide-gray-200 text-sm">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-4 py-2 text-left font-medium text-gray-500 uppercase">Profissional</th>
                <th class="px-4 py-2 text-left font-medium text-gray-500 uppercase">Cargo</th>
                <th class="px-4 py-2 text-left font-medium text-gray-500 uppercase">Clínicas</th>
                <th class="px-4 py-2 text-left font-medium text-gray-500 uppercase">Contato</th>
                <th class="px-4 py-2 text-left font-medium text-gray-500 uppercase">Ações</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-200">
            <?php $__empty_1 = true; $__currentLoopData = $profissionais; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $profissional): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <tr>
                <td class="px-4 py-2 whitespace-nowrap">
                    <div class="flex items-center space-x-2">
                        <?php
                            $pessoa = optional($profissional->usuario)->pessoa ?? $profissional->pessoa;
                            $initials = strtoupper(substr($pessoa->primeiro_nome, 0, 1) . substr($pessoa->ultimo_nome, 0, 1));
                        ?>
                        <?php if($pessoa->photo_path): ?>
                            <img src="<?php echo e(asset('storage/' . $pessoa->photo_path)); ?>" alt="<?php echo e($pessoa->primeiro_nome); ?>" class="w-8 h-8 rounded-full object-cover" />
                        <?php else: ?>
                            <div class="w-8 h-8 bg-gray-200 rounded-full flex items-center justify-center text-gray-700 uppercase"><?php echo e($initials); ?></div>
                        <?php endif; ?>
                        <div>
                            <div class="font-medium text-gray-700">
                                <?php echo e(optional($profissional->usuario->pessoa)->primeiro_nome ?? $profissional->pessoa->primeiro_nome); ?>

                                <?php echo e(optional($profissional->usuario->pessoa)->ultimo_nome ?? $profissional->pessoa->ultimo_nome); ?>

                            </div>
                            <?php if(optional($profissional->usuario)->especialidade): ?>
                                <div class="text-xs text-gray-500">
                                    <?php echo e(optional($profissional->usuario)->especialidade); ?>

                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </td>
                <td class="px-4 py-2 whitespace-nowrap"><?php echo e($profissional->cargo ?? '-'); ?></td>
                <td class="px-4 py-2 whitespace-nowrap space-x-1">
                    <?php $__currentLoopData = $profissional->clinics; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $clinic): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <span class="inline-block px-2 py-1 bg-gray-100 rounded text-xs text-gray-600"><?php echo e($clinic->nome); ?></span>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </td>
                <td class="px-4 py-2 whitespace-nowrap">
                    <div><?php echo e(optional($profissional->usuario->pessoa)->email ?? $profissional->pessoa->email); ?></div>
                    <div class="text-xs text-gray-500"><?php echo e(optional($profissional->usuario->pessoa)->phone ?? $profissional->pessoa->phone); ?></div>
                </td>
                <td class="px-4 py-2 whitespace-nowrap text-center">
                    <div class="flex items-center justify-center space-x-2">
                        <a href="<?php echo e(route('profissionais.edit', $profissional)); ?>" class="text-gray-600 hover:text-blue-600" title="Editar">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m0 0a2.5 2.5 0 01-3.536 3.536L9 20.036l-4 1 1-4 6.232-6.232a2.5 2.5 0 013.536-3.536z" />
                            </svg>
                        </a>
                        <a href="#" class="text-gray-600 hover:text-blue-600" title="Ver agenda">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </a>
                        <a href="#" class="text-gray-600 hover:text-blue-600" title="Financeiro">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 1.343-3 3s1.343 3 3 3 3 1.343 3 3-1.343 3-3 3m0-12V4m0 16v-4m0 4c1.657 0 3-1.343 3-3s-1.343-3-3-3-3-1.343-3-3 1.343-3 3-3" />
                            </svg>
                        </a>
                    </div>
                </td>
            </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <tr>
                <td colspan="5" class="px-4 py-2 text-center">Nenhum profissional cadastrado.</td>
            </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\dentix\resources\views/profissionais/index.blade.php ENDPATH**/ ?>