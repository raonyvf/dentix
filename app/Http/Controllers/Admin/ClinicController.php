<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Clinic;
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
        return view('admin.clinics.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nome' => 'required',
            'cnpj' => 'required',
            'responsavel' => 'required',
            'plano' => 'nullable',
            'idioma_preferido' => 'nullable',
        ]);
        Clinic::create($data);
        return redirect()->route('clinics.index');
    }

    public function edit(Clinic $clinic)
    {
        return view('admin.clinics.edit', compact('clinic'));
    }

    public function update(Request $request, Clinic $clinic)
    {
        $data = $request->validate([
            'nome' => 'required',
            'cnpj' => 'required',
            'responsavel' => 'required',
            'plano' => 'nullable',
            'idioma_preferido' => 'nullable',
        ]);
        $clinic->update($data);
        return redirect()->route('clinics.index');
    }

    public function destroy(Clinic $clinic)
    {
        $clinic->delete();
        return redirect()->route('clinics.index');
    }
}
