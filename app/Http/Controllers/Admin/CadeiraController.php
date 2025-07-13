<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Cadeira;
use App\Models\Unidade;
use Illuminate\Http\Request;

class CadeiraController extends Controller
{
    public function index()
    {
        $cadeiras = Cadeira::all();
        return view('admin.cadeiras.index', compact('cadeiras'));
    }

    public function create()
    {
        $unidades = Unidade::all();
        return view('admin.cadeiras.create', compact('unidades'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'unidade_id' => 'required|exists:unidades,id',
            'nome' => 'required',
            'especialidade' => 'required',
            'status' => 'required',
            'horarios_disponiveis' => 'required',
        ]);

        Cadeira::create($data);

        return redirect()->route('cadeiras.index')->with('success', 'Cadeira salva com sucesso.');
    }

    public function edit(Cadeira $cadeira)
    {
        $unidades = Unidade::all();
        return view('admin.cadeiras.edit', compact('cadeira', 'unidades'));
    }

    public function update(Request $request, Cadeira $cadeira)
    {
        $data = $request->validate([
            'unidade_id' => 'required|exists:unidades,id',
            'nome' => 'required',
            'especialidade' => 'required',
            'status' => 'required',
            'horarios_disponiveis' => 'required',
        ]);

        $cadeira->update($data);

        return redirect()->route('cadeiras.index')->with('success', 'Cadeira atualizada com sucesso.');
    }
}
