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
}
