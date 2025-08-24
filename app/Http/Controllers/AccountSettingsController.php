<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

class AccountSettingsController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        return view('account.settings', compact('user'));
    }
}
