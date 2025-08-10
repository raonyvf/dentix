<?php
    $diaSemana = $diaSemana ?? null;
    $out = $out ?? false;
    $horarioClinica = $clinic?->horarios->firstWhere('dia_semana', $diaSemana);

    $inicio = \Carbon\Carbon::parse($it->hora_inicio);
    $fim = \Carbon\Carbon::parse($it->hora_fim);

    $totalMinutos = 0;
    $offsetPercentual = 0;
    if ($horarioClinica) {
        $inicioClinica = \Carbon\Carbon::parse($horarioClinica->hora_inicio);
        $fimClinica = \Carbon\Carbon::parse($horarioClinica->hora_fim);
        $totalMinutos = $inicioClinica->diffInMinutes($fimClinica);
        $offsetMinutos = $inicioClinica->diffInMinutes($inicio);
        $offsetPercentual = $totalMinutos > 0 ? ($offsetMinutos / $totalMinutos) * 100 : 0;
    }

    $duracao = $inicio->diffInMinutes($fim);
    $percentual = $totalMinutos > 0 ? ($duracao / $totalMinutos) * 100 : 0;
?>
<div class="mb-2 p-2 rounded bg-emerald-50 text-sm escala-card relative cursor-pointer <?php echo e($out ? 'opacity-50' : ''); ?>" data-id="<?php echo e($it->id); ?>" data-profissional="<?php echo e($it->profissional_id); ?>" data-hora-inicio="<?php echo e($it->hora_inicio); ?>" data-hora-fim="<?php echo e($it->hora_fim); ?>" data-cadeira="<?php echo e($it->cadeira_id); ?>" data-date="<?php echo e(\Carbon\Carbon::parse($it->semana)->addDays($it->dia_semana - 1)->format('Y-m-d')); ?>">
    <div class="font-semibold whitespace-nowrap overflow-hidden text-ellipsis">
        <?php echo e(optional($it->profissional->pessoa)->primeiro_nome); ?> <?php echo e(optional($it->profissional->pessoa)->ultimo_nome); ?>

    </div>
    <div><?php echo e($it->hora_inicio); ?> – <?php echo e($it->hora_fim); ?></div>
    <div class="text-xs text-gray-600">
        <?php echo e(optional($it->profissional->usuario)->especialidade ?? $it->profissional->cargo); ?>

    </div>
    <div class="mt-1 h-2 rounded bg-emerald-400" style="width: <?php echo e($percentual); ?>%; margin-left: <?php echo e($offsetPercentual); ?>%"></div>
</div>
<?php /**PATH C:\laragon\www\dentix\resources\views/escalas/partials/card.blade.php ENDPATH**/ ?>