<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames((['title', 'chartId' => null]));

foreach ($attributes->all() as $__key => $__value) {
    if (in_array($__key, $__propNames)) {
        $$__key = $$__key ?? $__value;
    } else {
        $__newAttributes[$__key] = $__value;
    }
}

$attributes = new \Illuminate\View\ComponentAttributeBag($__newAttributes);

unset($__propNames);
unset($__newAttributes);

foreach (array_filter((['title', 'chartId' => null]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars); ?>

<?php
    $id = $chartId ?: \Illuminate\Support\Str::slug($title);
?>

<div class="bg-white rounded-lg shadow p-4">
    <div class="mb-2 flex items-center justify-between">
        <h3 class="text-lg font-semibold text-gray-700"><?php echo e($title); ?></h3>
        <?php echo e($header ?? ''); ?>

    </div>
    <div class="h-64">
        <canvas id="<?php echo e($id); ?>" class="w-full h-full"></canvas>
    </div>
    <?php echo e($slot ?? ''); ?>

</div><?php /**PATH C:\laragon\www\dentix\resources\views/components/dashboard/chart.blade.php ENDPATH**/ ?>