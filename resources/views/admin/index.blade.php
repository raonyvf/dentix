@extends('layouts.app')

@section('content')
<h1>Administração</h1>
<p>Gerencie clínicas, unidades e cadeiras de atendimento</p>
<div class="grid">
    <div class="card">
        <h3>Clínicas</h3>
        <p>Gerencie as informações das clínicas cadastradas</p>
        <small>Dados da empresa • Planos de assinatura • Responsáveis legais</small><br>
        <a class="btn" href="{{ route('clinicas.index') }}">Gerenciar Clínicas</a>
    </div>
    <div class="card">
        <h3>Unidades</h3>
        <p>Gerencie as unidades de atendimento</p>
        <small>Endereços e contatos • Horários • Vinculação com clínicas</small><br>
        <a class="btn" href="{{ route('unidades.index') }}">Gerenciar Unidades</a>
    </div>
    <div class="card">
        <h3>Cadeiras</h3>
        <p>Gerencie as cadeiras de atendimento</p>
        <small>Identificação • Status de funcionamento • Horários disponíveis</small><br>
        <a class="btn" href="{{ route('cadeiras.index') }}">Gerenciar Cadeiras</a>
    </div>
</div>
<hr>
<p>Ações Rápidas:</p>
<ul>
    <li><a href="{{ route('clinicas.create') }}">+ Nova Clínica</a></li>
    <li><a href="{{ route('unidades.create') }}">+ Nova Unidade</a></li>
    <li><a href="{{ route('cadeiras.create') }}">+ Nova Cadeira</a></li>
</ul>
@endsection
