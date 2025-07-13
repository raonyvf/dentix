<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Clinic;
use App\Models\Plano;
use App\Rules\Cnpj;
use Illuminate\Http\Request;

class ClinicController extends Controller
{
    public function index()
    {
        $clinics = Clinic::all();
        return view('admin.clinics.index', compact('clinics'));
    }

    public function create()
    {
        $planos = Plano::all();
        return view('admin.clinics.create', compact('planos'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nome' => 'required',
            'cnpj' => ['required', new Cnpj],
            'responsavel' => 'required',
            'plano_id' => 'required|exists:planos,id',
        ]);

        Clinic::create($data);

        return redirect()->route('clinicas.index')->with('success', 'Clínica salva com sucesso.');
    }

    public function edit(Clinic $clinic)
    {
        $planos = Plano::all();
        return view('admin.clinics.edit', compact('clinic', 'planos'));
    }

    public function update(Request $request, Clinic $clinic)
    {
        $data = $request->validate([
            'nome' => 'required',
            'cnpj' => ['required', new Cnpj],
            'responsavel' => 'required',
            'plano_id' => 'required|exists:planos,id',
        ]);

        $clinic->update($data);

        return redirect()->route('clinicas.index')->with('success', 'Clínica atualizada com sucesso.');
    }
}
