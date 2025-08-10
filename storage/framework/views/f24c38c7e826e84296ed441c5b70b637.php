

<?php $__env->startSection('content'); ?>
<?php echo $__env->make('partials.breadcrumbs', ['crumbs' => [
    ['label' => 'Dashboard', 'url' => route('admin.index')],
    ['label' => 'Gestão Financeira']
]], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<div class="flex items-start justify-between mb-6">
    <div>
        <h1 class="text-2xl font-bold">Gestão Financeira</h1>
        <p class="text-gray-600">Controle financeiro completo do consultório</p>
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
        <a href="#" class="py-2 px-4 bg-emerald-600 text-white rounded hover:bg-emerald-700 text-sm">+ Novo Recebimento</a>
        <a href="#" class="py-2 px-4 bg-blue-600 text-white rounded hover:bg-blue-700 text-sm">+ Novo Pagamento</a>
        <a href="#" class="py-2 px-4 border rounded text-sm">📊 Relatórios</a>
    </div>
</div>
<div class="mb-6" x-data="{ filtro: 'Mensal' }">
    <div class="flex gap-2">
        <template x-for="opt in ['Diário','Semanal','Mensal','Anual','Personalizado']" :key="opt">
            <button @click="filtro = opt" :class="filtro === opt ? 'bg-primary text-white' : 'bg-white border text-gray-700'" class="px-3 py-1 rounded text-sm" x-text="opt"></button>
        </template>
    </div>
</div>
<?php
    $saldoColor = $saldoAtual < 0 ? 'bg-red-500' : 'bg-emerald-500';
?>
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    <?php if (isset($component)) { $__componentOriginal8386ee894ecad1461945a79620ef4b0a = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal8386ee894ecad1461945a79620ef4b0a = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.financeiro.card','data' => ['label' => 'Saldo Atual','value' => 'R$ '.number_format($saldoAtual,2,',','.'),'color' => $saldoColor,'icon' => '<svg xmlns=\'http://www.w3.org/2000/svg\' class=\'w-6 h-6\' fill=\'none\' viewBox=\'0 0 24 24\' stroke=\'currentColor\'><path stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'2\' d=\'M12 8c-1.657 0-3 1.343-3 3s1.343 3 3 3 3 1.343 3 3-1.343 3-3 3m0-12V4m0 16v-4m0 4c1.657 0 3-1.343 3-3s-1.343-3-3-3-3-1.343-3-3 1.343-3 3-3\'/></svg>']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('financeiro.card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['label' => 'Saldo Atual','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute('R$ '.number_format($saldoAtual,2,',','.')),'color' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($saldoColor),'icon' => '<svg xmlns=\'http://www.w3.org/2000/svg\' class=\'w-6 h-6\' fill=\'none\' viewBox=\'0 0 24 24\' stroke=\'currentColor\'><path stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'2\' d=\'M12 8c-1.657 0-3 1.343-3 3s1.343 3 3 3 3 1.343 3 3-1.343 3-3 3m0-12V4m0 16v-4m0 4c1.657 0 3-1.343 3-3s-1.343-3-3-3-3-1.343-3-3 1.343-3 3-3\'/></svg>']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal8386ee894ecad1461945a79620ef4b0a)): ?>
<?php $attributes = $__attributesOriginal8386ee894ecad1461945a79620ef4b0a; ?>
<?php unset($__attributesOriginal8386ee894ecad1461945a79620ef4b0a); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal8386ee894ecad1461945a79620ef4b0a)): ?>
<?php $component = $__componentOriginal8386ee894ecad1461945a79620ef4b0a; ?>
<?php unset($__componentOriginal8386ee894ecad1461945a79620ef4b0a); ?>
<?php endif; ?>
    <?php if (isset($component)) { $__componentOriginal8386ee894ecad1461945a79620ef4b0a = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal8386ee894ecad1461945a79620ef4b0a = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.financeiro.card','data' => ['label' => 'Receitas (Mês)','value' => 'R$ '.number_format($receitasMes,2,',','.'),'color' => 'bg-emerald-500','icon' => '<svg xmlns=\'http://www.w3.org/2000/svg\' class=\'w-6 h-6\' fill=\'none\' viewBox=\'0 0 24 24\' stroke=\'currentColor\'><path stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'2\' d=\'M12 8c-1.657 0-3 1.343-3 3s1.343 3 3 3 3 1.343 3 3-1.343 3-3 3m0-12V4m0 16v-4m0 4c1.657 0 3-1.343 3-3s-1.343-3-3-3-3-1.343-3-3 1.343-3 3-3\'/></svg>']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('financeiro.card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['label' => 'Receitas (Mês)','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute('R$ '.number_format($receitasMes,2,',','.')),'color' => 'bg-emerald-500','icon' => '<svg xmlns=\'http://www.w3.org/2000/svg\' class=\'w-6 h-6\' fill=\'none\' viewBox=\'0 0 24 24\' stroke=\'currentColor\'><path stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'2\' d=\'M12 8c-1.657 0-3 1.343-3 3s1.343 3 3 3 3 1.343 3 3-1.343 3-3 3m0-12V4m0 16v-4m0 4c1.657 0 3-1.343 3-3s-1.343-3-3-3-3-1.343-3-3 1.343-3 3-3\'/></svg>']); ?>
         <?php $__env->slot('value', null, []); ?> 
            R$ <?php echo e(number_format($receitasMes, 2, ',', '.')); ?>

            <span class="block text-xs"><?php echo e($receitasAnterior ? round((($receitasMes - $receitasAnterior)/$receitasAnterior)*100,1) : 0); ?>% vs mês anterior</span>
         <?php $__env->endSlot(); ?>
     <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal8386ee894ecad1461945a79620ef4b0a)): ?>
<?php $attributes = $__attributesOriginal8386ee894ecad1461945a79620ef4b0a; ?>
<?php unset($__attributesOriginal8386ee894ecad1461945a79620ef4b0a); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal8386ee894ecad1461945a79620ef4b0a)): ?>
<?php $component = $__componentOriginal8386ee894ecad1461945a79620ef4b0a; ?>
<?php unset($__componentOriginal8386ee894ecad1461945a79620ef4b0a); ?>
<?php endif; ?>
    <?php if (isset($component)) { $__componentOriginal8386ee894ecad1461945a79620ef4b0a = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal8386ee894ecad1461945a79620ef4b0a = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.financeiro.card','data' => ['label' => 'Despesas (Mês)','value' => 'R$ '.number_format($despesasMes,2,',','.'),'color' => 'bg-red-500','icon' => '<svg xmlns=\'http://www.w3.org/2000/svg\' class=\'w-6 h-6\' fill=\'none\' viewBox=\'0 0 24 24\' stroke=\'currentColor\'><path stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'2\' d=\'M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6 6 0 10-12 0v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9\'/></svg>']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('financeiro.card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['label' => 'Despesas (Mês)','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute('R$ '.number_format($despesasMes,2,',','.')),'color' => 'bg-red-500','icon' => '<svg xmlns=\'http://www.w3.org/2000/svg\' class=\'w-6 h-6\' fill=\'none\' viewBox=\'0 0 24 24\' stroke=\'currentColor\'><path stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'2\' d=\'M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6 6 0 10-12 0v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9\'/></svg>']); ?>
         <?php $__env->slot('value', null, []); ?> 
            R$ <?php echo e(number_format($despesasMes, 2, ',', '.')); ?>

            <span class="block text-xs"><?php echo e($despesasAnterior ? round((($despesasMes - $despesasAnterior)/$despesasAnterior)*100,1) : 0); ?>% vs mês anterior</span>
         <?php $__env->endSlot(); ?>
     <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal8386ee894ecad1461945a79620ef4b0a)): ?>
<?php $attributes = $__attributesOriginal8386ee894ecad1461945a79620ef4b0a; ?>
<?php unset($__attributesOriginal8386ee894ecad1461945a79620ef4b0a); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal8386ee894ecad1461945a79620ef4b0a)): ?>
<?php $component = $__componentOriginal8386ee894ecad1461945a79620ef4b0a; ?>
<?php unset($__componentOriginal8386ee894ecad1461945a79620ef4b0a); ?>
<?php endif; ?>
    <?php if (isset($component)) { $__componentOriginal8386ee894ecad1461945a79620ef4b0a = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal8386ee894ecad1461945a79620ef4b0a = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.financeiro.card','data' => ['label' => 'A Receber','value' => 'R$ '.number_format($aReceberValor,2,',','.'),'color' => 'bg-yellow-500','icon' => '<svg xmlns=\'http://www.w3.org/2000/svg\' class=\'w-6 h-6\' fill=\'none\' viewBox=\'0 0 24 24\' stroke=\'currentColor\'><path stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'2\' d=\'M3 3h18M9 3v18m6-18v18M3 9h18M3 15h18\'/></svg>']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('financeiro.card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['label' => 'A Receber','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute('R$ '.number_format($aReceberValor,2,',','.')),'color' => 'bg-yellow-500','icon' => '<svg xmlns=\'http://www.w3.org/2000/svg\' class=\'w-6 h-6\' fill=\'none\' viewBox=\'0 0 24 24\' stroke=\'currentColor\'><path stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'2\' d=\'M3 3h18M9 3v18m6-18v18M3 9h18M3 15h18\'/></svg>']); ?>
         <?php $__env->slot('value', null, []); ?> 
            R$ <?php echo e(number_format($aReceberValor,2,',','.')); ?> (<?php echo e($aReceberCount); ?>)
         <?php $__env->endSlot(); ?>
     <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal8386ee894ecad1461945a79620ef4b0a)): ?>
<?php $attributes = $__attributesOriginal8386ee894ecad1461945a79620ef4b0a; ?>
<?php unset($__attributesOriginal8386ee894ecad1461945a79620ef4b0a); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal8386ee894ecad1461945a79620ef4b0a)): ?>
<?php $component = $__componentOriginal8386ee894ecad1461945a79620ef4b0a; ?>
<?php unset($__componentOriginal8386ee894ecad1461945a79620ef4b0a); ?>
<?php endif; ?>
</div>
<div class="bg-white rounded-lg shadow p-4 mb-6">
    <h3 class="text-lg font-semibold mb-2">Formas de Pagamento</h3>
    <p class="text-sm text-gray-500">Distribuição das receitas por forma de pagamento</p>
    <canvas id="formas-chart" class="w-full h-64" height="256"></canvas>
</div>
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <div class="bg-white rounded-lg shadow p-4">
        <h3 class="text-lg font-semibold mb-2">Próximos Recebimentos</h3>
        <p class="text-sm text-gray-500 mb-2">Pagamentos previstos para os próximos 7 dias</p>
        <ul class="divide-y divide-gray-200 text-sm">
            <?php $__currentLoopData = $proximosRecebimentos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $r): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <li class="py-2 flex justify-between items-center">
                <div>
                    <p class="font-medium"><?php echo e($r['paciente']); ?></p>
                    <p class="text-gray-500"><?php echo e($r['tratamento']); ?> • <?php echo e($r['unidade']); ?></p>
                </div>
                <div class="text-right">
                    <p class="<?php echo e('text-yellow-600'); ?>">R$ <?php echo e(number_format($r['valor'],2,',','.')); ?></p>
                    <p class="text-xs text-gray-500"><?php echo e($r['vencimento']); ?></p>
                </div>
            </li>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </ul>
    </div>
    <div class="bg-white rounded-lg shadow p-4">
        <h3 class="text-lg font-semibold mb-2">Próximos Pagamentos</h3>
        <p class="text-sm text-gray-500 mb-2">Despesas previstas para os próximos 7 dias</p>
        <ul class="divide-y divide-gray-200 text-sm">
            <?php $__currentLoopData = $proximosPagamentos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
@@ -97,73 +83,36 @@
                </div>
                <div class="text-right">
                    <p class="text-red-600">R$ <?php echo e(number_format($p['valor'],2,',','.')); ?></p>
                    <p class="text-xs text-gray-500"><?php echo e($p['vencimento']); ?></p>
                </div>
            </li>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </ul>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const createChart = (id, config) => {
            const el = document.getElementById(id);
            if (!el || !window.Chart) return;

            const existing = Chart.getChart ? Chart.getChart(el) : null;
            if (existing) existing.destroy();

            new Chart(el, config);
        };

        createChart('formas-chart', {
            type: 'doughnut',
            data: {
                labels: <?php echo json_encode(collect($formasPagamento)->pluck('label'), 15, 512) ?>,
                datasets: [{ data: <?php echo json_encode(collect($formasPagamento)->pluck('percent'), 15, 512) ?>, backgroundColor: ['#3b82f6','#10b981','#f59e0b','#6366f1'] }]
            },
            options: { responsive: true, maintainAspectRatio: false }
        });
    });
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\dentix\resources\views/financeiro/index.blade.php ENDPATH**/ ?>