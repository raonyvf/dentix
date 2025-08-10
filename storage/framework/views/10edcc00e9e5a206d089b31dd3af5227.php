

<?php $__env->startSection('content'); ?>
<?php echo $__env->make('partials.breadcrumbs', ['crumbs' => [
    ['label' => 'Dashboard', 'url' => route('admin.index')],
    ['label' => 'Pacientes', 'url' => route('pacientes.index')],
    ['label' => 'Novo']
]], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<div class="w-full bg-white p-6 rounded-lg shadow" x-data="{ menorIdade: '<?php echo e(old('menor_idade', 'Não')); ?>' }">
    <h1 class="text-xl font-semibold mb-4">Novo Paciente</h1>
    <?php if($errors->any()): ?>
        <?php if (isset($component)) { $__componentOriginal9f8e81b29df95f108aa662b57c2bb9b3 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal9f8e81b29df95f108aa662b57c2bb9b3 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.alert-error','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('alert-error'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
            <div>Por favor, preencha todos os campos obrigatórios (*).</div>
            <ul class="list-disc list-inside mt-2">
                <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <li><?php echo e($error); ?></li>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </ul>
         <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal9f8e81b29df95f108aa662b57c2bb9b3)): ?>
<?php $attributes = $__attributesOriginal9f8e81b29df95f108aa662b57c2bb9b3; ?>
<?php unset($__attributesOriginal9f8e81b29df95f108aa662b57c2bb9b3); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal9f8e81b29df95f108aa662b57c2bb9b3)): ?>
<?php $component = $__componentOriginal9f8e81b29df95f108aa662b57c2bb9b3; ?>
<?php unset($__componentOriginal9f8e81b29df95f108aa662b57c2bb9b3); ?>
<?php endif; ?>
    <?php endif; ?>
    <form method="POST" action="<?php echo e(route('pacientes.store')); ?>" class="space-y-6" novalidate>
        <?php echo csrf_field(); ?>
        <div class="rounded-sm border border-stroke bg-gray-50 p-4">
            <h2 class="mb-4 text-sm font-medium text-gray-700">Informações Básicas</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="text-sm font-medium text-gray-700 mb-2 block">Nome <span class="text-red-500">*</span></label>
                    <input class="w-full rounded border-[1.5px] border-stroke bg-gray-2 py-3 px-5 text-sm text-black focus:border-primary focus:outline-none" type="text" name="primeiro_nome" value="<?php echo e(old('primeiro_nome')); ?>" required />
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-700 mb-2 block">Nome do meio</label>
                    <input class="w-full rounded border-[1.5px] border-stroke bg-gray-2 py-3 px-5 text-sm text-black focus:border-primary focus:outline-none" type="text" name="nome_meio" value="<?php echo e(old('nome_meio')); ?>" />
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-700 mb-2 block">Último nome <span class="text-red-500">*</span></label>
                    <input class="w-full rounded border-[1.5px] border-stroke bg-gray-2 py-3 px-5 text-sm text-black focus:border-primary focus:outline-none" type="text" name="ultimo_nome" value="<?php echo e(old('ultimo_nome')); ?>" required />
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-700 mb-2 block">Data de nascimento <span class="text-red-500">*</span></label>
                    <input class="datepicker w-full rounded border-[1.5px] border-stroke bg-gray-2 py-3 px-5 text-sm text-black focus:border-primary focus:outline-none" type="text" name="data_nascimento" value="<?php echo e(old('data_nascimento')); ?>" required readonly />
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-700 mb-2 block">Sexo <span class="text-red-500">*</span></label>
                    <select name="sexo" class="w-full rounded border-[1.5px] border-stroke bg-gray-2 py-3 px-5 text-sm text-black focus:border-primary focus:outline-none" required>
                        <option value="">Selecione</option>
                        <option value="Masculino" <?php if(old('sexo')==='Masculino'): echo 'selected'; endif; ?>>Masculino</option>
                        <option value="Feminino" <?php if(old('sexo')==='Feminino'): echo 'selected'; endif; ?>>Feminino</option>
                        <option value="Outro" <?php if(old('sexo')==='Outro'): echo 'selected'; endif; ?>>Outro</option>
                    </select>
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-700 mb-2 block">CPF <span x-show="menorIdade !== 'Sim'" x-cloak class="text-red-500">*</span></label>
                    <input class="w-full rounded border-[1.5px] border-stroke bg-gray-2 py-3 px-5 text-sm text-black focus:border-primary focus:outline-none" type="text" name="cpf" value="<?php echo e(old('cpf')); ?>" x-bind:required="menorIdade !== 'Sim'" />
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-700 mb-2 block">Menor de idade?</label>
                    <select name="menor_idade" x-model="menorIdade" class="w-full rounded border-[1.5px] border-stroke bg-gray-2 py-3 px-5 text-sm text-black focus:border-primary focus:outline-none">
                        <option value="Não">Não</option>
                        <option value="Sim">Sim</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="rounded-sm border border-stroke bg-gray-50 p-4" x-show="menorIdade === 'Sim'" x-cloak>
            <h2 class="mb-4 text-sm font-medium text-gray-700">Dados do Responsável</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="text-sm font-medium text-gray-700 mb-2 block">Nome do responsável <span x-show="menorIdade === 'Sim'" x-cloak class="text-red-500">*</span></label>
                    <input class="w-full rounded border-[1.5px] border-stroke bg-gray-2 py-3 px-5 text-sm text-black focus:border-primary focus:outline-none" type="text" name="responsavel_primeiro_nome" value="<?php echo e(old('responsavel_primeiro_nome')); ?>" x-bind:required="menorIdade === 'Sim'" />
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-700 mb-2 block">Nome do meio</label>
                    <input class="w-full rounded border-[1.5px] border-stroke bg-gray-2 py-3 px-5 text-sm text-black focus:border-primary focus:outline-none" type="text" name="responsavel_nome_meio" value="<?php echo e(old('responsavel_nome_meio')); ?>" />
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-700 mb-2 block">Último nome <span x-show="menorIdade === 'Sim'" x-cloak class="text-red-500">*</span></label>
                    <input class="w-full rounded border-[1.5px] border-stroke bg-gray-2 py-3 px-5 text-sm text-black focus:border-primary focus:outline-none" type="text" name="responsavel_ultimo_nome" value="<?php echo e(old('responsavel_ultimo_nome')); ?>" x-bind:required="menorIdade === 'Sim'" />
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-700 mb-2 block">CPF do responsável <span x-show="menorIdade === 'Sim'" x-cloak class="text-red-500">*</span></label>
                    <input class="w-full rounded border-[1.5px] border-stroke bg-gray-2 py-3 px-5 text-sm text-black focus:border-primary focus:outline-none" type="text" name="responsavel_cpf" value="<?php echo e(old('responsavel_cpf')); ?>" x-bind:required="menorIdade === 'Sim'" />
                </div>
            </div>
        </div>
        <div class="rounded-sm border border-stroke bg-gray-50 p-4">
            <h2 class="mb-4 text-sm font-medium text-gray-700">Contato</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="text-sm font-medium text-gray-700 mb-2 block">Telefone <span class="text-red-500">*</span></label>
                    <input class="w-full rounded border-[1.5px] border-stroke bg-gray-2 py-3 px-5 text-sm text-black focus:border-primary focus:outline-none" type="text" name="telefone" value="<?php echo e(old('telefone')); ?>" required />
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-700 mb-2 block">Whatsapp</label>
                    <input class="w-full rounded border-[1.5px] border-stroke bg-gray-2 py-3 px-5 text-sm text-black focus:border-primary focus:outline-none" type="text" name="whatsapp" value="<?php echo e(old('whatsapp')); ?>" />
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-700 mb-2 block">E-mail</label>
                    <input class="w-full rounded border-[1.5px] border-stroke bg-gray-2 py-3 px-5 text-sm text-black focus:border-primary focus:outline-none" type="email" name="email" value="<?php echo e(old('email')); ?>" />
                </div>
            </div>
        </div>
        <div class="rounded-sm border border-stroke bg-gray-50 p-4">
            <h2 class="mb-4 text-sm font-medium text-gray-700">Endereço</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="text-sm font-medium text-gray-700 mb-2 block">CEP <span class="text-red-500">*</span></label>
                    <input class="w-full rounded border-[1.5px] border-stroke bg-gray-2 py-3 px-5 text-sm text-black focus:border-primary focus:outline-none" type="text" name="cep" value="<?php echo e(old('cep')); ?>" required />
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-700 mb-2 block">Logradouro <span class="text-red-500">*</span></label>
                    <input class="w-full rounded border-[1.5px] border-stroke bg-gray-2 py-3 px-5 text-sm text-black focus:border-primary focus:outline-none" type="text" name="logradouro" value="<?php echo e(old('logradouro')); ?>" required />
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-700 mb-2 block">Número <span class="text-red-500">*</span></label>
                    <input class="w-full rounded border-[1.5px] border-stroke bg-gray-2 py-3 px-5 text-sm text-black focus:border-primary focus:outline-none" type="text" name="numero" value="<?php echo e(old('numero')); ?>" required />
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-700 mb-2 block">Complemento</label>
                    <input class="w-full rounded border-[1.5px] border-stroke bg-gray-2 py-3 px-5 text-sm text-black focus:border-primary focus:outline-none" type="text" name="complemento" value="<?php echo e(old('complemento')); ?>" />
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-700 mb-2 block">Bairro <span class="text-red-500">*</span></label>
                    <input class="w-full rounded border-[1.5px] border-stroke bg-gray-2 py-3 px-5 text-sm text-black focus:border-primary focus:outline-none" type="text" name="bairro" value="<?php echo e(old('bairro')); ?>" required />
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-700 mb-2 block">Cidade <span class="text-red-500">*</span></label>
                    <input class="w-full rounded border-[1.5px] border-stroke bg-gray-2 py-3 px-5 text-sm text-black focus:border-primary focus:outline-none" type="text" name="cidade" value="<?php echo e(old('cidade')); ?>" required />
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-700 mb-2 block">Estado <span class="text-red-500">*</span></label>
                    <input class="w-full rounded border-[1.5px] border-stroke bg-gray-2 py-3 px-5 text-sm text-black focus:border-primary focus:outline-none" type="text" name="estado" value="<?php echo e(old('estado')); ?>" required />
                </div>
            </div>
        </div>
        <div class="rounded-sm border border-stroke bg-gray-50 p-4">
            <h2 class="mb-4 text-sm font-medium text-gray-700">Portal do Paciente</h2>
            <label class="inline-flex items-center space-x-2">
                <input type="checkbox" name="create_user" value="1" class="rounded border-stroke">
                <span class="text-sm text-gray-700">Criar usuário para acesso</span>
            </label>
        </div>
        <div class="flex justify-between pt-4">
            <a href="<?php echo e(route('pacientes.index')); ?>" class="py-2 px-4 rounded border border-stroke text-gray-700">Cancelar</a>
            <button type="submit" class="py-2 px-4 bg-blue-600 text-white rounded hover:bg-blue-700">Salvar Paciente</button>
        </div>
    </form>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', ['hideErrors' => true], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\dentix\resources\views/pacientes/create.blade.php ENDPATH**/ ?>