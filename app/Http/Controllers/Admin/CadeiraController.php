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
}
