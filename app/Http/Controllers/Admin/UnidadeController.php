<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Unidade;
use Illuminate\Http\Request;

class UnidadeController extends Controller
{
    public function index()
    {
        $unidades = Unidade::all();
        return view('admin.unidades.index', compact('unidades'));
    }

    public function create()
    {
        return view('admin.unidades.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nome' => 'required',
            'endereco' => 'required',
            'cidade' => 'required',
            'estado' => 'required',
            'contato' => 'nullable',
            'horarios_funcionamento' => 'nullable',
        ]);
        Unidade::create($data);
        return redirect()->route('unidades.index');
    }

    public function edit(Unidade $unidade)
    {
        return view('admin.unidades.edit', compact('unidade'));
    }

    public function update(Request $request, Unidade $unidade)
    {
        $data = $request->validate([
            'nome' => 'required',
            'endereco' => 'required',
            'cidade' => 'required',
            'estado' => 'required',
            'contato' => 'nullable',
            'horarios_funcionamento' => 'nullable',
        ]);
        $unidade->update($data);
        return redirect()->route('unidades.index');
    }

    public function destroy(Unidade $unidade)
    {
        $unidade->delete();
        return redirect()->route('unidades.index');
    }
}
