<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Cadeira;
use App\Models\Clinic;
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
        $clinics = Clinic::all();
        return view('admin.cadeiras.create', compact('clinics')); 
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'clinic_id' => 'required|exists:clinics,id',
            'nome' => 'required',
            'especialidade' => 'required',
            'status' => 'required',
        ]);

        if ($data['clinic_id'] != auth()->user()->clinic_id) {
            abort(403);
        }

        Cadeira::create($data);

        return redirect()->route('cadeiras.index')->with('success', 'Cadeira salva com sucesso.');
    }

    public function edit(Cadeira $cadeira)
    {
        $clinics = Clinic::all();
        return view('admin.cadeiras.edit', compact('cadeira', 'clinics'));
    }

    public function update(Request $request, Cadeira $cadeira)
    {
        $data = $request->validate([
            'clinic_id' => 'required|exists:clinics,id',
            'nome' => 'required',
            'especialidade' => 'required',
            'status' => 'required',
        ]);

        if ($cadeira->clinic_id != auth()->user()->clinic_id || $data['clinic_id'] != auth()->user()->clinic_id) {
            abort(403);
        }

        $cadeira->update($data);

        return redirect()->route('cadeiras.index')->with('success', 'Cadeira atualizada com sucesso.');
    }
}
