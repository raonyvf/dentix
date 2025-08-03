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
        $cadeiras = Cadeira::with("clinic")->get();
        return view('admin.cadeiras.index', compact('cadeiras'));
    }

    public function create()
    {
        $user = auth()->user();
        if ($user->isOrganizationAdmin()) {
            $clinics = Clinic::all();
        } else {
            $clinics = $user->clinics()->get();
        }
        return view('admin.cadeiras.create', compact('clinics'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'clinic_id' => 'required|exists:clinicas,id',
            'nome' => 'required',
            'status' => 'required',
        ]);

        $currentClinic = app()->bound('clinic_id') ? app('clinic_id') : null;
        $user = auth()->user();
        if (! $user->isOrganizationAdmin() && ! $user->isSuperAdmin()) {
            if (is_null($currentClinic) || $data['clinic_id'] != $currentClinic || ! $user->clinics->contains($currentClinic)) {
                abort(403);
            }

        }

        Cadeira::create($data);

        return redirect()->route('cadeiras.index')->with('success', 'Cadeira salva com sucesso.');
    }

    public function edit(Cadeira $cadeira)
    {
        $user = auth()->user();
        if (! $user->isOrganizationAdmin() && $cadeira->clinic_id != (app()->bound('clinic_id') ? app('clinic_id') : null)) {
            abort(403);
        }

        if ($user->isOrganizationAdmin()) {
            $clinics = Clinic::all();
        } else {
            $clinics = $user->clinics()->get();
        }
        return view('admin.cadeiras.edit', compact('cadeira', 'clinics'));
    }

    public function update(Request $request, Cadeira $cadeira)
    {
        $data = $request->validate([
            'clinic_id' => 'required|exists:clinicas,id',
            'nome' => 'required',
            'status' => 'required',
        ]);

        $currentClinic = app()->bound('clinic_id') ? app('clinic_id') : null;
        $user = auth()->user();
        if (! $user->isOrganizationAdmin() && ! $user->isSuperAdmin()) {
            if (is_null($currentClinic) || $cadeira->clinic_id != $currentClinic || $data['clinic_id'] != $currentClinic || ! $user->clinics->contains($currentClinic)) {
                abort(403);
            }

        }

        $cadeira->update($data);

        return redirect()->route('cadeiras.index')->with('success', 'Cadeira atualizada com sucesso.');
    }
}
