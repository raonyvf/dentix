<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>" x-data="{ sidebarCollapsed: JSON.parse(localStorage.getItem('sidebarCollapsed') ?? 'false') }" x-init="$watch('sidebarCollapsed', val => localStorage.setItem('sidebarCollapsed', val))" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title><?php echo e(config('app.name')); ?></title>
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
    <script>
        document.addEventListener('alpine:init', () => {

        });
        document.addEventListener('DOMContentLoaded', () => {

        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body class="flex h-full bg-gray-100">
    <?php if(auth()->guard()->check()): ?>
        <?php if (! (isset($hideNav) && $hideNav)): ?>
            <aside :class="sidebarCollapsed ? 'w-20' : 'w-64'" class="h-full bg-white border-r shadow transition-all duration-300">
                <?php echo $__env->make('partials.sidebar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
            </aside>
        <?php endif; ?>
    <?php endif; ?>
    <div class="flex flex-col flex-1 min-h-screen">
        <?php if(auth()->guard()->check()): ?>
            <?php if (! (isset($hideNav) && $hideNav)): ?>
                <?php echo $__env->make('partials.topbar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
            <?php endif; ?>
        <?php endif; ?>
        <main class="flex-1 p-6 overflow-y-auto">
            <?php if($errors->any() && !(isset($hideErrors) && $hideErrors)): ?>
                <?php echo $__env->make('components.alert-error', ['slot' => $errors->first()], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
            <?php endif; ?>
            <?php if(session('success')): ?>
                <?php echo $__env->make('components.alert-success', ['slot' => session('success')], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
            <?php endif; ?>
            <?php echo $__env->yieldContent('content'); ?>
        </main>
    </div>
    <?php echo $__env->yieldPushContent('scripts'); ?>
</body>
</html><?php /**PATH C:\laragon\www\dentix\resources\views/layouts/app.blade.php ENDPATH**/ ?>