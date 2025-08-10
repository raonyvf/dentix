<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    public function index()
    {
        $totalPacientes = random_int(300, 500);
        $consultasHoje = random_int(10, 40);
        $cancelamentosHoje = random_int(0, 10);
        $faturamentoDiario = random_int(5000, 20000);

        $consultas = [
            'agendadas' => random_int(15, 30),
            'confirmadas' => random_int(10, 25),
            'canceladas' => random_int(0, 5),
        ];
        $consultas['realizadas'] = max(0, $consultas['confirmadas'] - $consultas['canceladas']);

        return view('dashboard', [
            'totalPacientes' => $totalPacientes,
            'consultasHoje' => $consultasHoje,
            'cancelamentosHoje' => $cancelamentosHoje,
            'faturamentoDiario' => 'R$ ' . number_format($faturamentoDiario, 2, ',', '.'),
            'consultas' => $consultas,
        ]);
    }
}
