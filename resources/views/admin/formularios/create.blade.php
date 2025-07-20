@extends('layouts.app')

@section('content')
@include('partials.breadcrumbs', ['crumbs' => [
    ['label' => 'Dashboard', 'url' => route('admin.index')],
    ['label' => 'Formulários', 'url' => route('formularios.index')],
    ['label' => 'Novo']
]])
<div x-data="questionsForm()" class="w-full bg-white p-6 rounded-lg shadow">
    <h1 class="text-xl font-semibold mb-4">Novo Formulário</h1>
    @if ($errors->any())
        <x-alert-error>{{ implode(', ', $errors->all()) }}</x-alert-error>
    @endif
    <form method="POST" action="{{ route('formularios.store') }}" class="space-y-4">
        @csrf
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Nome do formulário</label>
            <input type="text" name="nome" value="{{ old('nome') }}" required class="w-full rounded border border-stroke py-2 px-3" />
        </div>
        <template x-for="(q, index) in questions" :key="index">
            <div class="border p-4 rounded mb-4">
                <div class="flex justify-between items-center mb-2">
                    <h2 class="font-medium">Pergunta <span x-text="index + 1"></span></h2>
                    <button type="button" class="text-red-600" @click="remove(index)">Remover</button>
                </div>
                <div class="space-y-2">
                    <div>
                        <label class="block text-sm">Enunciado</label>
                        <input type="text" :name="`perguntas[`+index+`][enunciado]`" x-model="q.enunciado" class="w-full rounded border border-stroke py-2 px-3" required>
                    </div>
                    <div>
                        <label class="block text-sm">Tipo</label>
                        <select :name="`perguntas[`+index+`][tipo]`" x-model="q.tipo" class="w-full rounded border border-stroke py-2 px-3">
                            <option value="texto">Texto</option>
                            <option value="select">Select</option>
                            <option value="checkbox">Checkbox</option>
                            <option value="radio">Radio</option>
                        </select>
                    </div>
                    <div x-show="['select','checkbox','radio'].includes(q.tipo)">
                        <label class="block text-sm">Opções (separadas por vírgula)</label>
                        <input type="text" :name="`perguntas[`+index+`][opcoes]`" x-model="q.opcoes" class="w-full rounded border border-stroke py-2 px-3" />
                    </div>
                </div>
            </div>
        </template>
        <button type="button" class="py-2 px-4 bg-green-600 text-white rounded" @click="add">Nova Pergunta</button>
        <div>
            <button type="submit" class="py-2 px-4 bg-blue-600 text-white rounded">Salvar</button>
        </div>
    </form>
</div>
<script>
function questionsForm() {
    return {
        questions: [],
        add() { this.questions.push({enunciado:'',tipo:'texto',opcoes:''}); },
        remove(i) { this.questions.splice(i,1); }
    };
}
</script>
@endsection
