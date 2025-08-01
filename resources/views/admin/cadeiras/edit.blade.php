@extends('layouts.app', ['hideErrors' => true])

@section('content')
@include('partials.breadcrumbs', ['crumbs' => [
    ['label' => 'Dashboard', 'url' => route('admin.index')],
    ['label' => 'Cadeiras', 'url' => route('cadeiras.index')],
    ['label' => 'Editar']
]])
<div class="w-full bg-white p-6 rounded-lg shadow">
    <h1 class="text-xl font-semibold mb-4">Editar Cadeira</h1>
    @if ($errors->any())
        <x-alert-error>
            <div>Por favor, preencha todos os campos obrigatórios (<span class="text-red-500">*</span>).</div>
            <ul class="list-disc list-inside mt-2">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </x-alert-error>
    @endif
    <form method="POST" action="{{ route('cadeiras.update', $cadeira) }}" class="space-y-4" novalidate>
        @csrf
        @method('PUT')
        <div>
            <label class="mb-2 block text-sm font-medium text-gray-700">Clínica <span class="text-red-500">*</span></label>
            <select name="clinic_id" required class="w-full rounded border-[1.5px] border-stroke bg-gray-2 py-3 px-5 text-sm text-black focus:border-primary focus:outline-none">
                <option value="">Selecione</option>
                @foreach ($clinics as $clinic)
                    <option value="{{ $clinic->id }}" @selected(old('clinic_id', $cadeira->clinic_id) == $clinic->id)>{{ $clinic->nome }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="mb-2 block text-sm font-medium text-gray-700">Nome <span class="text-red-500">*</span></label>
            <input class="w-full rounded border-[1.5px] border-stroke bg-gray-2 py-3 px-5 text-sm text-black focus:border-primary focus:outline-none" type="text" name="nome" value="{{ old('nome', $cadeira->nome) }}" required />
        </div>
        <div>
            <label class="mb-2 block text-sm font-medium text-gray-700">Status <span class="text-red-500">*</span></label>
            <select name="status" required class="w-full rounded border-[1.5px] border-stroke bg-gray-2 py-3 px-5 text-sm text-black focus:border-primary focus:outline-none">
                <option value="Ativa" @selected(old('status', $cadeira->status) === 'Ativa')>Ativa</option>
                <option value="Desativa" @selected(old('status', $cadeira->status) === 'Desativa')>Desativa</option>
            </select>
        </div>
        <button type="submit" class="py-2 px-4 bg-blue-600 text-white rounded hover:bg-blue-700">Salvar</button>
    </form>
</div>
@endsection
