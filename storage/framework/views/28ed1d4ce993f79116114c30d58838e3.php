<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames((['crumbs' => []]));

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

foreach (array_filter((['crumbs' => []]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars); ?>
<nav class="text-sm text-gray-500 mb-4" aria-label="Breadcrumb">
    <ol class="list-reset flex items-center space-x-2">
        <?php $__currentLoopData = $crumbs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $crumb): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <li>
                <?php if(isset($crumb['url'])): ?>
                    <a href="<?php echo e($crumb['url']); ?>" class="text-blue-600 hover:underline"><?php echo e($crumb['label']); ?></a>
                <?php else: ?>
                    <span><?php echo e($crumb['label']); ?></span>
                <?php endif; ?>
            </li>
            <?php if (! ($loop->last)): ?>
                <li>/</li>
            <?php endif; ?>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </ol>
</nav>
<?php /**PATH C:\laragon\www\dentix\resources\views/partials/breadcrumbs.blade.php ENDPATH**/ ?>