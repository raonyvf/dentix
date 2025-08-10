

<?php $__env->startSection('content'); ?>
<?php echo $__env->make('partials.breadcrumbs', ['crumbs' => [
    ['label' => 'Dashboard', 'url' => route('admin.index')],
    ['label' => 'Pacientes']
]], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<div class="mb-6 flex items-start justify-between">
    <div>
        <h1 class="text-2xl font-bold">Pacientes</h1>
        <p class="text-gray-600">Gerencie todos os pacientes da clínica</p>
    </div>
    <a href="<?php echo e(route('pacientes.create')); ?>" class="py-2 px-4 bg-blue-600 text-white rounded hover:bg-blue-700 flex items-center">
        + Novo Paciente
    </a>
</div>
<div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
    <div class="p-4 bg-white rounded-lg shadow">
        <p class="text-sm text-gray-500">Total de Pacientes</p>
        <p class="mt-2 text-3xl font-semibold text-gray-700">2.834</p>
        <p class="text-xs text-gray-500">+180 nos últimos 30 dias</p>
    </div>
    <div class="p-4 bg-white rounded-lg shadow">
        <p class="text-sm text-gray-500">Consultas Agendadas</p>
        <p class="mt-2 text-2xl font-semibold text-gray-700">148</p>
        <p class="text-xs text-gray-500">Próximos 30 dias</p>
    </div>
    <div class="p-4 bg-white rounded-lg shadow">
        <p class="text-sm text-gray-500">Retornos Pendentes</p>
        <p class="mt-2 text-2xl font-semibold text-gray-700">29</p>
        <p class="text-xs text-gray-500">Necessitam reagendamento</p>
    </div>
</div>
<div class="bg-white rounded-lg shadow">
    <div class="flex items-center justify-between px-4 pt-4">
        <h2 class="text-lg font-semibold">Lista de Pacientes</h2>
        <input type="text" placeholder="Buscar paciente..." class="border rounded-lg px-3 py-2 text-sm w-72" />
    </div>
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Nome</th>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Responsável</th>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Idade</th>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Telefone</th>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Whatsapp</th>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Última Consulta</th>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Próxima Consulta</th>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Ações</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                <?php $__currentLoopData = $pacientes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $paciente): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td class="px-4 py-2 whitespace-nowrap"><?php echo e($paciente->pessoa->primeiro_nome); ?> <?php echo e($paciente->pessoa->ultimo_nome); ?></td>
                        <td class="px-4 py-2 whitespace-nowrap">
                            <?php echo e($paciente->menor_idade
                                    ? (trim(
                                        ($paciente->responsavel_primeiro_nome ?? '') . ' ' .
                                        ($paciente->responsavel_nome_meio ? $paciente->responsavel_nome_meio . ' ' : '') .
                                        ($paciente->responsavel_ultimo_nome ?? '')
                                    ) ?: '-')
                                    : '-'); ?>

                        </td>
                        <td class="px-4 py-2 whitespace-nowrap"><?php echo e(\Carbon\Carbon::parse($paciente->pessoa->data_nascimento)->age); ?></td>
                        <td class="px-4 py-2 whitespace-nowrap"><?php echo e($paciente->pessoa->phone); ?></td>
                        <td class="px-4 py-2 whitespace-nowrap"><?php echo e($paciente->pessoa->whatsapp); ?></td>
                        <td class="px-4 py-2 whitespace-nowrap">-</td>
                        <td class="px-4 py-2 whitespace-nowrap">-</td>
                        <td class="px-4 py-2 whitespace-nowrap text-center">
                            <div class="flex items-center justify-center space-x-2">
                                <a href="<?php echo e(route('pacientes.show', $paciente)); ?>" class="text-gray-600 hover:text-blue-600" title="Ver">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                </a>
                                <a href="<?php echo e(route('pacientes.edit', $paciente)); ?>" class="text-gray-600 hover:text-blue-600" title="Editar">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m0 0a2.5 2.5 0 01-3.536 3.536L9 20.036l-4 1 1-4 6.232-6.232a2.5 2.5 0 013.536-3.536z" />
                                    </svg>
                                </a>
                                <a href="#" class="text-gray-600 hover:text-blue-600" title="Ver Prontuário">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 12v.01M7 8h10M7 12h.01M7 16h.01M9 16h6m2 4H7a2 2 0 01-2-2V6a2 2 0 012-2h5l2 2h5a2 2 0 012 2v10a2 2 0 01-2 2z" />
                                    </svg>
                                </a>
                                <form action="<?php echo e(route('pacientes.destroy', $paciente)); ?>" method="POST" onsubmit="return confirm('Tem certeza que deseja excluir este paciente?');">
                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field('DELETE'); ?>
                                    <button type="submit" class="text-red-600 hover:text-red-800" title="Excluir">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6M1 7h22M10 7V4a1 1 0 011-1h2a1 1 0 011 1v3" />
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>
    </div>
</div>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\dentix\resources\views/pacientes/index.blade.php ENDPATH**/ ?>