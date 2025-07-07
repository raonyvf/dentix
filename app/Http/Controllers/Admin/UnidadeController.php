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
}
