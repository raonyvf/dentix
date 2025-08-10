

<?php $__env->startSection('content'); ?>
<?php echo $__env->make('partials.breadcrumbs', ['crumbs' => [
    ['label' => 'Dashboard', 'url' => route('admin.index')],
    ['label' => 'Profissionais', 'url' => route('profissionais.index')],
    ['label' => 'Novo']
]], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<div class="w-full bg-white p-6 rounded-lg shadow" x-data="{ activeTab: 'dados', selectedClinics: <?php echo \Illuminate\Support\Js::from(old('clinics', []))->toHtml() ?> }">
    <h1 class="text-xl font-semibold mb-4">Novo Profissional</h1>
    <div class="border-b mb-6">
        <nav class="-mb-px flex space-x-4" aria-label="Tabs">
            <button type="button" @click="activeTab = 'dados'" :class="activeTab === 'dados' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'" class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">Dados cadastrais</button>
            <button type="button" @click="activeTab = 'adm'" :class="activeTab === 'adm' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'" class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">Dados admissionais</button>
            <button type="button" @click="activeTab = 'rem'" :class="activeTab === 'rem' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'" class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">Remuneração</button>
        </nav>
    </div>
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
    <form method="POST" action="<?php echo e(route('profissionais.store')); ?>" enctype="multipart/form-data" class="space-y-6" novalidate>
        <?php echo csrf_field(); ?>
        <div x-show="activeTab === 'dados'" class="space-y-6">
        <?php if (isset($component)) { $__componentOriginalcaa10005d418b3a0a6ea2520ac5f11c4 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalcaa10005d418b3a0a6ea2520ac5f11c4 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.accordion-section','data' => ['title' => 'Dados pessoais','open' => true]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('accordion-section'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Dados pessoais','open' => true]); ?>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="text-sm font-medium text-gray-700 mb-2 block">Nome <span class="text-red-500">*</span></label>
                    <input type="text" name="primeiro_nome" value="<?php echo e(old('primeiro_nome')); ?>" class="w-full rounded border-[1.5px] border-stroke bg-gray-2 py-3 px-5 text-sm text-black focus:border-primary focus:outline-none" required />
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-700 mb-2 block">Nome do meio</label>
                    <input type="text" name="nome_meio" value="<?php echo e(old('nome_meio')); ?>" class="w-full rounded border-[1.5px] border-stroke bg-gray-2 py-3 px-5 text-sm text-black focus:border-primary focus:outline-none" />
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-700 mb-2 block">Último nome <span class="text-red-500">*</span></label>
                    <input type="text" name="ultimo_nome" value="<?php echo e(old('ultimo_nome')); ?>" class="w-full rounded border-[1.5px] border-stroke bg-gray-2 py-3 px-5 text-sm text-black focus:border-primary focus:outline-none" required />
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-700 mb-2 block">Data de nascimento <span class="text-red-500">*</span></label>
                    <input type="text" name="data_nascimento" value="<?php echo e(old('data_nascimento')); ?>" class="datepicker w-full rounded border-[1.5px] border-stroke bg-gray-2 py-3 px-5 text-sm text-black focus:border-primary focus:outline-none" required readonly />
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
                    <label class="text-sm font-medium text-gray-700 mb-2 block">Naturalidade <span class="text-red-500">*</span></label>
                    <input type="text" name="naturalidade" value="<?php echo e(old('naturalidade')); ?>" class="w-full rounded border-[1.5px] border-stroke bg-gray-2 py-3 px-5 text-sm text-black focus:border-primary focus:outline-none" required />
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-700 mb-2 block">Nacionalidade <span class="text-red-500">*</span></label>
                    <input type="text" name="nacionalidade" value="<?php echo e(old('nacionalidade')); ?>" class="w-full rounded border-[1.5px] border-stroke bg-gray-2 py-3 px-5 text-sm text-black focus:border-primary focus:outline-none" required />
                </div>
                <div class="sm:col-span-2">
                    <label class="text-sm font-medium text-gray-700 mb-2 block">Foto</label>
                    <input type="file" name="foto" class="w-full text-sm text-gray-700" />
                </div>
            </div>
         <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalcaa10005d418b3a0a6ea2520ac5f11c4)): ?>
<?php $attributes = $__attributesOriginalcaa10005d418b3a0a6ea2520ac5f11c4; ?>
<?php unset($__attributesOriginalcaa10005d418b3a0a6ea2520ac5f11c4); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalcaa10005d418b3a0a6ea2520ac5f11c4)): ?>
<?php $component = $__componentOriginalcaa10005d418b3a0a6ea2520ac5f11c4; ?>
<?php unset($__componentOriginalcaa10005d418b3a0a6ea2520ac5f11c4); ?>
<?php endif; ?>
        <?php if (isset($component)) { $__componentOriginalcaa10005d418b3a0a6ea2520ac5f11c4 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalcaa10005d418b3a0a6ea2520ac5f11c4 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.accordion-section','data' => ['title' => 'Documentos']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('accordion-section'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Documentos']); ?>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="text-sm font-medium text-gray-700 mb-2 block">CPF <span class="text-red-500">*</span></label>
                    <input type="text" name="cpf" value="<?php echo e(old('cpf')); ?>" class="w-full rounded border-[1.5px] border-stroke bg-gray-2 py-3 px-5 text-sm text-black focus:border-primary focus:outline-none" required />
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-700 mb-2 block">RG</label>
                    <input type="text" name="rg" value="<?php echo e(old('rg')); ?>" class="w-full rounded border-[1.5px] border-stroke bg-gray-2 py-3 px-5 text-sm text-black focus:border-primary focus:outline-none" />
                </div>
            </div>
         <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalcaa10005d418b3a0a6ea2520ac5f11c4)): ?>
<?php $attributes = $__attributesOriginalcaa10005d418b3a0a6ea2520ac5f11c4; ?>
<?php unset($__attributesOriginalcaa10005d418b3a0a6ea2520ac5f11c4); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalcaa10005d418b3a0a6ea2520ac5f11c4)): ?>
<?php $component = $__componentOriginalcaa10005d418b3a0a6ea2520ac5f11c4; ?>
<?php unset($__componentOriginalcaa10005d418b3a0a6ea2520ac5f11c4); ?>
<?php endif; ?>
        <?php if (isset($component)) { $__componentOriginalcaa10005d418b3a0a6ea2520ac5f11c4 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalcaa10005d418b3a0a6ea2520ac5f11c4 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.accordion-section','data' => ['title' => 'Dados de contato']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('accordion-section'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Dados de contato']); ?>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="text-sm font-medium text-gray-700 mb-2 block">Email <span class="text-red-500">*</span></label>
                    <input type="email" name="email" value="<?php echo e(old('email')); ?>" class="w-full rounded border-[1.5px] border-stroke bg-gray-2 py-3 px-5 text-sm text-black focus:border-primary focus:outline-none" required />
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-700 mb-2 block">Telefone <span class="text-red-500">*</span></label>
                    <input type="text" name="telefone" value="<?php echo e(old('telefone')); ?>" class="w-full rounded border-[1.5px] border-stroke bg-gray-2 py-3 px-5 text-sm text-black focus:border-primary focus:outline-none" required />
                </div>
            </div>
         <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalcaa10005d418b3a0a6ea2520ac5f11c4)): ?>
<?php $attributes = $__attributesOriginalcaa10005d418b3a0a6ea2520ac5f11c4; ?>
<?php unset($__attributesOriginalcaa10005d418b3a0a6ea2520ac5f11c4); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalcaa10005d418b3a0a6ea2520ac5f11c4)): ?>
<?php $component = $__componentOriginalcaa10005d418b3a0a6ea2520ac5f11c4; ?>
<?php unset($__componentOriginalcaa10005d418b3a0a6ea2520ac5f11c4); ?>
<?php endif; ?>
        <?php if (isset($component)) { $__componentOriginalcaa10005d418b3a0a6ea2520ac5f11c4 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalcaa10005d418b3a0a6ea2520ac5f11c4 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.accordion-section','data' => ['title' => 'Endereço']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('accordion-section'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Endereço']); ?>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="text-sm font-medium text-gray-700 mb-2 block">CEP <span class="text-red-500">*</span></label>
                    <input type="text" name="cep" value="<?php echo e(old('cep')); ?>" class="w-full rounded border-[1.5px] border-stroke bg-gray-2 py-3 px-5 text-sm text-black focus:border-primary focus:outline-none" required />
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-700 mb-2 block">Logradouro <span class="text-red-500">*</span></label>
                    <input type="text" name="logradouro" value="<?php echo e(old('logradouro')); ?>" class="w-full rounded border-[1.5px] border-stroke bg-gray-2 py-3 px-5 text-sm text-black focus:border-primary focus:outline-none" required />
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-700 mb-2 block">Número <span class="text-red-500">*</span></label>
                    <input type="text" name="numero" value="<?php echo e(old('numero')); ?>" class="w-full rounded border-[1.5px] border-stroke bg-gray-2 py-3 px-5 text-sm text-black focus:border-primary focus:outline-none" required />
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-700 mb-2 block">Complemento</label>
                    <input type="text" name="complemento" value="<?php echo e(old('complemento')); ?>" class="w-full rounded border-[1.5px] border-stroke bg-gray-2 py-3 px-5 text-sm text-black focus:border-primary focus:outline-none" />
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-700 mb-2 block">Bairro <span class="text-red-500">*</span></label>
                    <input type="text" name="bairro" value="<?php echo e(old('bairro')); ?>" class="w-full rounded border-[1.5px] border-stroke bg-gray-2 py-3 px-5 text-sm text-black focus:border-primary focus:outline-none" required />
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-700 mb-2 block">Cidade <span class="text-red-500">*</span></label>
                    <input type="text" name="cidade" value="<?php echo e(old('cidade')); ?>" class="w-full rounded border-[1.5px] border-stroke bg-gray-2 py-3 px-5 text-sm text-black focus:border-primary focus:outline-none" required />
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-700 mb-2 block">Estado <span class="text-red-500">*</span></label>
                    <input type="text" name="estado" value="<?php echo e(old('estado')); ?>" class="w-full rounded border-[1.5px] border-stroke bg-gray-2 py-3 px-5 text-sm text-black focus:border-primary focus:outline-none" required />
                </div>
            </div>
         <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalcaa10005d418b3a0a6ea2520ac5f11c4)): ?>
<?php $attributes = $__attributesOriginalcaa10005d418b3a0a6ea2520ac5f11c4; ?>
<?php unset($__attributesOriginalcaa10005d418b3a0a6ea2520ac5f11c4); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalcaa10005d418b3a0a6ea2520ac5f11c4)): ?>
<?php $component = $__componentOriginalcaa10005d418b3a0a6ea2520ac5f11c4; ?>
<?php unset($__componentOriginalcaa10005d418b3a0a6ea2520ac5f11c4); ?>
<?php endif; ?>
    </div>
    <div x-show="activeTab === 'adm'" x-cloak x-data="{ funcao: '<?php echo e(old('funcao')); ?>', tipo_contrato: '<?php echo e(old('tipo_contrato')); ?>' }" class="space-y-6">
        <?php if (isset($component)) { $__componentOriginalcaa10005d418b3a0a6ea2520ac5f11c4 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalcaa10005d418b3a0a6ea2520ac5f11c4 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.accordion-section','data' => ['title' => 'Dados funcionais','open' => true]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('accordion-section'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Dados funcionais','open' => true]); ?>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="text-sm font-medium text-gray-700 mb-2 block">Número do funcionário</label>
                    <input type="text" name="numero_funcionario" value="<?php echo e(old('numero_funcionario')); ?>" class="w-full rounded border-[1.5px] border-stroke bg-gray-2 py-3 px-5 text-sm text-black focus:border-primary focus:outline-none" />
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-700 mb-2 block">E-mail corporativo</label>
                    <input type="text" name="email_corporativo" value="<?php echo e(old('email_corporativo')); ?>" class="w-full rounded border-[1.5px] border-stroke bg-gray-2 py-3 px-5 text-sm text-black focus:border-primary focus:outline-none" />
                </div>
            </div>
         <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalcaa10005d418b3a0a6ea2520ac5f11c4)): ?>
<?php $attributes = $__attributesOriginalcaa10005d418b3a0a6ea2520ac5f11c4; ?>
<?php unset($__attributesOriginalcaa10005d418b3a0a6ea2520ac5f11c4); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalcaa10005d418b3a0a6ea2520ac5f11c4)): ?>
<?php $component = $__componentOriginalcaa10005d418b3a0a6ea2520ac5f11c4; ?>
<?php unset($__componentOriginalcaa10005d418b3a0a6ea2520ac5f11c4); ?>
<?php endif; ?>
        <?php if (isset($component)) { $__componentOriginalcaa10005d418b3a0a6ea2520ac5f11c4 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalcaa10005d418b3a0a6ea2520ac5f11c4 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.accordion-section','data' => ['title' => 'Contrato de Trabalho']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('accordion-section'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Contrato de Trabalho']); ?>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="text-sm font-medium text-gray-700 mb-2 block">Tipo de Contrato <span class="text-red-500">*</span></label>
                    <select name="tipo_contrato" x-model="tipo_contrato" class="w-full rounded border-[1.5px] border-stroke bg-gray-2 py-3 px-5 text-sm text-black focus:border-primary focus:outline-none" required>
                        <option value="">Selecione</option>
                        <option value="CLT" <?php if(old('tipo_contrato')==='CLT'): echo 'selected'; endif; ?>>CLT (Contrato com carteira assinada)</option>
                        <option value="PJ" <?php if(old('tipo_contrato')==='PJ'): echo 'selected'; endif; ?>>PJ (Pessoa Jurídica)</option>
                        <option value="Autônomo" <?php if(old('tipo_contrato')==='Autônomo'): echo 'selected'; endif; ?>>Autônomo</option>
                        <option value="Estágio" <?php if(old('tipo_contrato')==='Estágio'): echo 'selected'; endif; ?>>Estágio</option>
                        <option value="Temporário" <?php if(old('tipo_contrato')==='Temporário'): echo 'selected'; endif; ?>>Temporário</option>
                        <option value="Prestador de serviço" <?php if(old('tipo_contrato')==='Prestador de serviço'): echo 'selected'; endif; ?>>Prestador de serviço</option>
                        <option value="Outro" <?php if(old('tipo_contrato')==='Outro'): echo 'selected'; endif; ?>>Outro</option>
                    </select>
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-700 mb-2 block">Data de início do contrato <span class="text-red-500">*</span></label>
                    <input type="text" name="data_inicio_contrato" value="<?php echo e(old('data_inicio_contrato')); ?>" class="datepicker w-full rounded border-[1.5px] border-stroke bg-gray-2 py-3 px-5 text-sm text-black focus:border-primary focus:outline-none" required />
                </div>
                <div x-show="tipo_contrato && tipo_contrato !== 'CLT'" x-cloak>
                    <label class="text-sm font-medium text-gray-700 mb-2 block">Data de término do contrato</label>
                    <input type="text" name="data_fim_contrato" value="<?php echo e(old('data_fim_contrato')); ?>" class="datepicker w-full rounded border-[1.5px] border-stroke bg-gray-2 py-3 px-5 text-sm text-black focus:border-primary focus:outline-none" />
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-700 mb-2 block">Total de horas semanais</label>
                    <input type="number" name="total_horas_semanais" value="<?php echo e(old('total_horas_semanais')); ?>" class="w-full rounded border-[1.5px] border-stroke bg-gray-2 py-3 px-5 text-sm text-black focus:border-primary focus:outline-none" />
                </div>
                <div class="sm:col-span-2">
                    <label class="text-sm font-medium text-gray-700 mb-2 block">Clínicas <span class="text-red-500">*</span></label>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                        <?php $__currentLoopData = $clinics; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $clinic): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <label class="flex items-center space-x-2">
                                <input type="checkbox" name="clinics[]" x-model="selectedClinics" value="<?php echo e($clinic->id); ?>" <?php if(in_array($clinic->id, old('clinics', []))): echo 'checked'; endif; ?> class="rounded border-stroke" :required="selectedClinics.length === 0" />
                                <span><?php echo e($clinic->nome); ?></span>
                            </label>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-700 mb-2 block">Regime de trabalho <span class="text-red-500">*</span></label>
                    <select name="regime_trabalho" class="w-full rounded border-[1.5px] border-stroke bg-gray-2 py-3 px-5 text-sm text-black focus:border-primary focus:outline-none" required>
                        <option value="">Selecione</option>
                        <option value="Presencial" <?php if(old('regime_trabalho')==='Presencial'): echo 'selected'; endif; ?>>Presencial</option>
                        <option value="Remoto" <?php if(old('regime_trabalho')=='Remoto'): echo 'selected'; endif; ?>>Remoto</option>
                        <option value="Híbrido" <?php if(old('regime_trabalho')==='Híbrido'): echo 'selected'; endif; ?>>Híbrido</option>
                    </select>
                </div>
            </div>
         <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalcaa10005d418b3a0a6ea2520ac5f11c4)): ?>
<?php $attributes = $__attributesOriginalcaa10005d418b3a0a6ea2520ac5f11c4; ?>
<?php unset($__attributesOriginalcaa10005d418b3a0a6ea2520ac5f11c4); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalcaa10005d418b3a0a6ea2520ac5f11c4)): ?>
<?php $component = $__componentOriginalcaa10005d418b3a0a6ea2520ac5f11c4; ?>
<?php unset($__componentOriginalcaa10005d418b3a0a6ea2520ac5f11c4); ?>
<?php endif; ?>
        <?php if (isset($component)) { $__componentOriginalcaa10005d418b3a0a6ea2520ac5f11c4 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalcaa10005d418b3a0a6ea2520ac5f11c4 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.accordion-section','data' => ['title' => 'Atribuição']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('accordion-section'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Atribuição']); ?>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="text-sm font-medium text-gray-700 mb-2 block">Função <span class="text-red-500">*</span></label>
                    <select name="funcao" x-model="funcao" class="w-full rounded border-[1.5px] border-stroke bg-gray-2 py-3 px-5 text-sm text-black focus:border-primary focus:outline-none" required>
                        <option value="">Selecione</option>
                        <option value="Dentista" <?php if(old('funcao')==='Dentista'): echo 'selected'; endif; ?>>Dentista</option>
                        <option value="Assistencial" <?php if(old('funcao')==='Assistencial'): echo 'selected'; endif; ?>>Assistencial</option>
                        <option value="Administrativa" <?php if(old('funcao')==='Administrativa'): echo 'selected'; endif; ?>>Administrativa</option>
                        <option value="Recepcionista" <?php if(old('funcao')==='Recepcionista'): echo 'selected'; endif; ?>>Recepcionista</option>
                        <option value="Financeiro" <?php if(old('funcao')==='Financeiro'): echo 'selected'; endif; ?>>Financeiro</option>
                        <option value="Comercial" <?php if(old('funcao')==='Comercial'): echo 'selected'; endif; ?>>Comercial</option>
                        <option value="Outros" <?php if(old('funcao')==='Outros'): echo 'selected'; endif; ?>>Outros</option>
                    </select>
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-700 mb-2 block">Cargo <span class="text-red-500">*</span></label>
                    <input type="text" name="cargo" value="<?php echo e(old('cargo')); ?>" class="w-full rounded border-[1.5px] border-stroke bg-gray-2 py-3 px-5 text-sm text-black focus:border-primary focus:outline-none" required />
                </div>
            </div>
         <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalcaa10005d418b3a0a6ea2520ac5f11c4)): ?>
<?php $attributes = $__attributesOriginalcaa10005d418b3a0a6ea2520ac5f11c4; ?>
<?php unset($__attributesOriginalcaa10005d418b3a0a6ea2520ac5f11c4); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalcaa10005d418b3a0a6ea2520ac5f11c4)): ?>
<?php $component = $__componentOriginalcaa10005d418b3a0a6ea2520ac5f11c4; ?>
<?php unset($__componentOriginalcaa10005d418b3a0a6ea2520ac5f11c4); ?>
<?php endif; ?>
        <?php
            $registrosTitle = 'Registros';
        ?>
        <?php if (isset($component)) { $__componentOriginalcaa10005d418b3a0a6ea2520ac5f11c4 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalcaa10005d418b3a0a6ea2520ac5f11c4 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.accordion-section','data' => ['titleHtml' => $registrosTitle,'xShow' => 'funcao === \'Dentista\'','xCloak' => true]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('accordion-section'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title-html' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($registrosTitle),'x-show' => 'funcao === \'Dentista\'','x-cloak' => true]); ?>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="text-sm font-medium text-gray-700 mb-2 block">CRO <span x-show="funcao === 'Dentista'" class="text-red-500">*</span></label>
                    <input type="number" name="cro" value="<?php echo e(old('cro')); ?>" x-bind:required="funcao === 'Dentista'" class="w-full rounded border-[1.5px] border-stroke bg-gray-2 py-3 px-5 text-sm text-black focus:border-primary focus:outline-none" />
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-700 mb-2 block">UF do CRO <span x-show="funcao === 'Dentista'" class="text-red-500">*</span></label>
                    <select name="cro_uf" x-bind:required="funcao === 'Dentista'" class="w-full rounded border-[1.5px] border-stroke bg-gray-2 py-3 px-5 text-sm text-black focus:border-primary focus:outline-none">
                        <option value="">Selecione</option>
                        <?php $__currentLoopData = ['AC','AL','AP','AM','BA','CE','DF','ES','GO','MA','MT','MS','MG','PA','PB','PR','PE','PI','RJ','RN','RS','RO','RR','SC','SP','SE','TO']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $uf): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($uf); ?>" <?php if(old('cro_uf')===$uf): echo 'selected'; endif; ?>><?php echo e($uf); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
            </div>
         <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalcaa10005d418b3a0a6ea2520ac5f11c4)): ?>
<?php $attributes = $__attributesOriginalcaa10005d418b3a0a6ea2520ac5f11c4; ?>
<?php unset($__attributesOriginalcaa10005d418b3a0a6ea2520ac5f11c4); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalcaa10005d418b3a0a6ea2520ac5f11c4)): ?>
<?php $component = $__componentOriginalcaa10005d418b3a0a6ea2520ac5f11c4; ?>
<?php unset($__componentOriginalcaa10005d418b3a0a6ea2520ac5f11c4); ?>
<?php endif; ?>
    </div>
    <div x-show="activeTab === 'rem'" x-cloak class="space-y-6">
        <?php if (isset($component)) { $__componentOriginalcaa10005d418b3a0a6ea2520ac5f11c4 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalcaa10005d418b3a0a6ea2520ac5f11c4 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.accordion-section','data' => ['title' => 'Remuneração e Comissionamento','open' => true]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('accordion-section'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Remuneração e Comissionamento','open' => true]); ?>
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div class="sm:col-span-2 flex space-x-2">
                    <div class="flex-1">
                        <label class="text-sm font-medium text-gray-700 mb-2 block">Salário fixo <span class="text-red-500">*</span></label>
                        <input type="text" name="salario_fixo" value="<?php echo e(old('salario_fixo')); ?>" class="currency-brl w-full rounded border-[1.5px] border-stroke bg-gray-2 py-3 px-5 text-sm text-black focus:border-primary focus:outline-none" required />
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-700 mb-2 block">&nbsp;</label>
                        <select name="salario_periodo" class="w-full rounded border-[1.5px] border-stroke bg-gray-2 py-3 px-5 text-sm text-black focus:border-primary focus:outline-none">
                            <option value="mes" <?php if(old('salario_periodo')==='mes'): echo 'selected'; endif; ?>>Por mês</option>
                            <option value="dia" <?php if(old('salario_periodo')==='dia'): echo 'selected'; endif; ?>>Por dia</option>
                            <option value="hora" <?php if(old('salario_periodo')==='hora'): echo 'selected'; endif; ?>>Por hora</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="space-y-4 mt-4">
                <?php $__currentLoopData = $clinics; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $clinic): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="p-4 bg-gray-50 border rounded" x-show="selectedClinics.includes('<?php echo e($clinic->id); ?>')" x-cloak>
                        <h4 class="text-sm font-medium text-gray-700 mb-2"><?php echo e($clinic->nome); ?></h4>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="text-sm font-medium text-gray-700 mb-2 block">% de comissão</label>
                                <input type="number" step="0.01" min="0" max="100" name="comissoes[<?php echo e($clinic->id); ?>][comissao]" value="<?php echo e(old('comissoes.' . $clinic->id . '.comissao')); ?>" class="w-full rounded border-[1.5px] border-stroke bg-gray-2 py-3 px-5 text-sm text-black focus:border-primary focus:outline-none" />
                            </div>
                            <div>
                                <label class="text-sm font-medium text-gray-700 mb-2 block">% prótese</label>
                                <input type="number" step="0.01" min="0" max="100" name="comissoes[<?php echo e($clinic->id); ?>][protese]" value="<?php echo e(old('comissoes.' . $clinic->id . '.protese')); ?>" class="w-full rounded border-[1.5px] border-stroke bg-gray-2 py-3 px-5 text-sm text-black focus:border-primary focus:outline-none" />
                            </div>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
         <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalcaa10005d418b3a0a6ea2520ac5f11c4)): ?>
<?php $attributes = $__attributesOriginalcaa10005d418b3a0a6ea2520ac5f11c4; ?>
<?php unset($__attributesOriginalcaa10005d418b3a0a6ea2520ac5f11c4); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalcaa10005d418b3a0a6ea2520ac5f11c4)): ?>
<?php $component = $__componentOriginalcaa10005d418b3a0a6ea2520ac5f11c4; ?>
<?php unset($__componentOriginalcaa10005d418b3a0a6ea2520ac5f11c4); ?>
<?php endif; ?>
        <?php if (isset($component)) { $__componentOriginalcaa10005d418b3a0a6ea2520ac5f11c4 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalcaa10005d418b3a0a6ea2520ac5f11c4 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.accordion-section','data' => ['title' => 'Dados bancários']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('accordion-section'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Dados bancários']); ?>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="text-sm font-medium text-gray-700 mb-2 block">Nome do banco</label>
                    <input type="text" name="conta[nome_banco]" value="<?php echo e(old('conta.nome_banco')); ?>" class="w-full rounded border-[1.5px] border-stroke bg-gray-2 py-3 px-5 text-sm text-black focus:border-primary focus:outline-none" />
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-700 mb-2 block">Tipo de conta</label>
                    <select name="conta[tipo]" class="w-full rounded border-[1.5px] border-stroke bg-gray-2 py-3 px-5 text-sm text-black focus:border-primary focus:outline-none">
                        <option value="">Selecione</option>
                        <option value="Corrente" <?php if(old('conta.tipo')==='Corrente'): echo 'selected'; endif; ?>>Corrente</option>
                        <option value="Poupança" <?php if(old('conta.tipo')==='Poupança'): echo 'selected'; endif; ?>>Poupança</option>
                        <option value="Pagamento" <?php if(old('conta.tipo')==='Pagamento'): echo 'selected'; endif; ?>>Pagamento</option>
                    </select>
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-700 mb-2 block">Agência</label>
                    <input type="text" name="conta[agencia]" value="<?php echo e(old('conta.agencia')); ?>" class="w-full rounded border-[1.5px] border-stroke bg-gray-2 py-3 px-5 text-sm text-black focus:border-primary focus:outline-none" />
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-700 mb-2 block">Número da conta</label>
                    <input type="text" name="conta[numero]" value="<?php echo e(old('conta.numero')); ?>" class="w-full rounded border-[1.5px] border-stroke bg-gray-2 py-3 px-5 text-sm text-black focus:border-primary focus:outline-none" />
                </div>
                <div data-cpf-cnpj-group>
                    <label class="text-sm font-medium text-gray-700 mb-2 block">CPF/CNPJ do titular <span class="text-red-500" data-required-indicator>*</span></label>
                    <div class="flex items-center space-x-4 mb-2">
                        <label class="flex items-center space-x-1">
                            <input type="radio" name="conta[cpf_cnpj_tipo]" value="cpf" <?php if(old('conta.cpf_cnpj_tipo', 'cpf')==='cpf'): echo 'checked'; endif; ?> />
                            <span>CPF</span>
                        </label>
                        <label class="flex items-center space-x-1">
                            <input type="radio" name="conta[cpf_cnpj_tipo]" value="cnpj" <?php if(old('conta.cpf_cnpj_tipo')==='cnpj'): echo 'checked'; endif; ?> />
                            <span>CNPJ</span>
                        </label>
                    </div>
                    <input type="text" data-role="cpf_cnpj" name="conta[cpf_cnpj]" value="<?php echo e(old('conta.cpf_cnpj')); ?>" class="w-full rounded border-[1.5px] border-stroke bg-gray-2 py-3 px-5 text-sm text-black focus:border-primary focus:outline-none <?php $__errorArgs = ['conta.cpf_cnpj'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" />
                </div>
                <div class="sm:col-span-2">
                    <label class="text-sm font-medium text-gray-700 mb-2 block">Chave PIX</label>
                    <input type="text" name="chave_pix" value="<?php echo e(old('chave_pix')); ?>" class="w-full rounded border-[1.5px] border-stroke bg-gray-2 py-3 px-5 text-sm text-black focus:border-primary focus:outline-none" />
                </div>
            </div>
         <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalcaa10005d418b3a0a6ea2520ac5f11c4)): ?>
<?php $attributes = $__attributesOriginalcaa10005d418b3a0a6ea2520ac5f11c4; ?>
<?php unset($__attributesOriginalcaa10005d418b3a0a6ea2520ac5f11c4); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalcaa10005d418b3a0a6ea2520ac5f11c4)): ?>
<?php $component = $__componentOriginalcaa10005d418b3a0a6ea2520ac5f11c4; ?>
<?php unset($__componentOriginalcaa10005d418b3a0a6ea2520ac5f11c4); ?>
<?php endif; ?>
    </div>
        <div class="flex justify-between pt-4">
            <a href="<?php echo e(route('profissionais.index')); ?>" class="py-2 px-4 rounded border border-stroke text-gray-700">Cancelar</a>
            <button type="submit" class="py-2 px-4 bg-blue-600 text-white rounded hover:bg-blue-700">Salvar Profissional</button>
        </div>
    </form>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', ['hideErrors' => true], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\dentix\resources\views/profissionais/create.blade.php ENDPATH**/ ?>