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
            'plano' => 'required',
            'idioma_preferido' => 'required',
        ]);

        Clinic::create($data);

        return redirect()->route('clinicas.index');
    }
}
