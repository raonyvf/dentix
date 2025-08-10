<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames((['label', 'value', 'icon', 'color' => 'bg-blue-500']));

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

foreach (array_filter((['label', 'value', 'icon', 'color' => 'bg-blue-500']), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars); ?>
<div <?php echo e($attributes->merge(['class' => "flex items-center p-4 text-white rounded-lg $color"])); ?>>
    <div class="mr-4">
        <?php echo $icon; ?>

    </div>
    <div>
        <p class="text-sm"><?php echo e($label); ?></p>
        <p class="text-xl font-semibold"><?php echo e($value); ?></p>
    </div>
</div>
<?php /**PATH C:\laragon\www\dentix\resources\views/components/financeiro/card.blade.php ENDPATH**/ ?>