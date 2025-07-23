<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProfessionalController extends Controller
{
    public function index()
    {
        // Dados devem ser recuperados do banco via modelo Profissional
        $profissionais = [];
        $clinicas = [];
        return view('profissionais.index', compact('profissionais', 'clinicas'));
    }

    public function create()
    {
        return view('profissionais.create');
    }

    public function store(Request $request)
    {
        // TODO: implementar lógica de armazenamento
    }

    public function edit($id)
    {
        return view('profissionais.edit');
    }

    public function update(Request $request, $id)
    {
        // TODO: implementar lógica de atualização
    }

    public function destroy($id)
    {
        // TODO: implementar exclusão
    }
}
