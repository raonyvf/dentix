<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Cadeira;
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
        return view('admin.cadeiras.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'unidade_id' => 'required',
            'nome' => 'required',
            'especialidade' => 'nullable',
            'status' => 'nullable',
            'horarios_disponiveis' => 'nullable',
        ]);
        Cadeira::create($data);
        return redirect()->route('cadeiras.index');
    }

    public function edit(Cadeira $cadeira)
    {
        return view('admin.cadeiras.edit', compact('cadeira'));
    }

    public function update(Request $request, Cadeira $cadeira)
    {
        $data = $request->validate([
            'unidade_id' => 'required',
            'nome' => 'required',
            'especialidade' => 'nullable',
            'status' => 'nullable',
            'horarios_disponiveis' => 'nullable',
        ]);
        $cadeira->update($data);
        return redirect()->route('cadeiras.index');
    }

    public function destroy(Cadeira $cadeira)
    {
        $cadeira->delete();
        return redirect()->route('cadeiras.index');
    }
}
