<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames((['title', 'value', 'icon' => null, 'comparison' => null]));

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

foreach (array_filter((['title', 'value', 'icon' => null, 'comparison' => null]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars); ?>
<div class="rounded-sm border border-stroke bg-white p-5 shadow">
    <div class="flex items-center justify-between">
        <div>
            <p class="text-sm text-gray-500"><?php echo e($title); ?></p>
            <p class="mt-1 text-xl font-bold text-black"><?php echo e($value); ?></p>
        </div>
        <?php if($icon): ?>
            <div class="flex h-11 w-11 items-center justify-center rounded-full bg-primary/10 text-primary">
                <?php echo $icon; ?>

            </div>
        <?php endif; ?>
    </div>
    <?php if($comparison): ?>
        <p class="mt-2 text-xs text-gray-500"><?php echo e($comparison); ?></p>
    <?php endif; ?>
</div>
<?php /**PATH C:\laragon\www\dentix\resources\views/components/dashboard/stats-card.blade.php ENDPATH**/ ?>