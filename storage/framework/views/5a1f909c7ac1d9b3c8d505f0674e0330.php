<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames((['title', 'open' => false, 'titleHtml' => null]));

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

foreach (array_filter((['title', 'open' => false, 'titleHtml' => null]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars); ?>
<div x-data="{ open: <?php echo json_encode($open, 15, 512) ?> }" class="rounded-sm border border-stroke bg-gray-50">
    <button type="button" @click="open = !open" class="w-full flex justify-between items-center p-4">
        <span class="text-sm font-medium text-gray-700"><?php echo $titleHtml ?? e($title); ?></span>
        <svg class="w-4 h-4 transform transition-transform" :class="{ 'rotate-180': open }" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
        </svg>
    </button>
    <div x-show="open" x-collapse x-cloak class="p-4 border-t border-stroke">
        <?php echo e($slot); ?>

    </div>
</div>
<?php /**PATH C:\laragon\www\dentix\resources\views/components/accordion-section.blade.php ENDPATH**/ ?>