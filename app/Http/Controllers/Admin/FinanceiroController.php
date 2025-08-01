<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class FinanceiroController extends Controller
{
    public function index(Request $request)
    {
        $clinics = ['Unidade Centro', 'Unidade Norte', 'Unidade Sul'];

        $saldoAtual = random_int(-5000, 15000);
        $receitasMes = random_int(10000, 30000);
        $receitasAnterior = random_int(8000, 25000);
        $despesasMes = random_int(5000, 20000);
        $despesasAnterior = random_int(4000, 18000);
        $aReceberValor = random_int(1000, 8000);
        $aReceberCount = random_int(1, 20);

        $comparativo = collect($clinics)->map(function ($c) {
            return [
                'clinic' => $c,
                'receita' => random_int(5000, 15000),
                'despesa' => random_int(2000, 8000),
                'areceber' => random_int(0, 5000),
            ];
        });

        $meses = collect(range(0, 5))->map(function ($i) {
            $m = Carbon::now()->subMonths(5 - $i);
            return [
                'mes' => $m->format('m/Y'),
                'receita' => random_int(10000, 30000),
                'despesa' => random_int(5000, 20000),
            ];
        });

        $formasPagamento = [
            ['label' => 'Cartão de Crédito', 'percent' => 42],
            ['label' => 'Dinheiro', 'percent' => 18],
            ['label' => 'PIX', 'percent' => 28],
            ['label' => 'Boleto', 'percent' => 12],
        ];

        $proximosRecebimentos = [
            ['paciente' => 'Ana Silva', 'tratamento' => 'Clareamento', 'unidade' => 'Centro', 'valor' => 500, 'vencimento' => Carbon::now()->addDays(2)->format('d/m')],
            ['paciente' => 'João Souza', 'tratamento' => 'Implante', 'unidade' => 'Sul', 'valor' => 1200, 'vencimento' => Carbon::now()->addDays(4)->format('d/m')],
            ['paciente' => 'Maria Oliveira', 'tratamento' => 'Ortodontia', 'unidade' => 'Norte', 'valor' => 800, 'vencimento' => Carbon::now()->addDays(6)->format('d/m')],
        ];

        $proximosPagamentos = [
            ['nome' => 'Aluguel', 'tipo' => 'Despesa Fixa', 'unidade' => 'Centro', 'valor' => 1500, 'vencimento' => Carbon::now()->addDays(1)->format('d/m')],
            ['nome' => 'Compra de materiais', 'tipo' => 'Despesa Variável', 'unidade' => 'Sul', 'valor' => 700, 'vencimento' => Carbon::now()->addDays(3)->format('d/m')],
            ['nome' => 'Energia elétrica', 'tipo' => 'Despesa Fixa', 'unidade' => 'Norte', 'valor' => 900, 'vencimento' => Carbon::now()->addDays(5)->format('d/m')],
        ];

        return view('financeiro.index', [
            'clinics' => $clinics,
            'saldoAtual' => $saldoAtual,
            'receitasMes' => $receitasMes,
            'receitasAnterior' => $receitasAnterior,
            'despesasMes' => $despesasMes,
            'despesasAnterior' => $despesasAnterior,
            'aReceberValor' => $aReceberValor,
            'aReceberCount' => $aReceberCount,
            'comparativo' => $comparativo,
            'meses' => $meses,
            'formasPagamento' => $formasPagamento,
            'proximosRecebimentos' => $proximosRecebimentos,
            'proximosPagamentos' => $proximosPagamentos,
        ]);
    }
}
