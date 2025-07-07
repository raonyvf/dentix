<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Unidade;
use App\Models\Clinic;
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
        $clinics = Clinic::all();
        return view('admin.unidades.create', compact('clinics'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'clinic_id' => 'required|exists:clinics,id',
            'nome' => 'required',
            'endereco' => 'required',
            'cidade' => 'required',
            'estado' => 'required',
            'contato' => 'required',
            'horarios_funcionamento' => 'required',
        ]);

        Unidade::create($data);

        return redirect()->route('unidades.index');
    }
}
