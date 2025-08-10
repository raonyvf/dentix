<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Carbon;

class EstoqueController extends Controller
{
    public function index()
    {
        $clinics = ['Todas as Clínicas', 'Centro', 'Norte', 'Sul'];

        $produtos = collect([
            [
                'nome' => 'Luvas de Procedimento',
                'categoria' => 'Descartáveis',
                'centro' => random_int(0, 60),
                'norte' => random_int(0, 60),
                'sul' => random_int(0, 60),
                'minimo' => 50,
                'valor_unitario' => 1.5,
                'ultima_compra' => Carbon::now()->subDays(random_int(1, 30))->format('d/m/Y'),
            ],
            [
                'nome' => 'Máscaras Cirúrgicas',
                'categoria' => 'Descartáveis',
                'centro' => random_int(0, 80),
                'norte' => random_int(0, 80),
                'sul' => random_int(0, 80),
                'minimo' => 75,
                'valor_unitario' => 0.8,
                'ultima_compra' => Carbon::now()->subDays(random_int(1, 30))->format('d/m/Y'),
            ],
            [
                'nome' => 'Anestésico',
                'categoria' => 'Medicamentos',
                'centro' => random_int(0, 40),
                'norte' => random_int(0, 40),
                'sul' => random_int(0, 40),
                'minimo' => 30,
                'valor_unitario' => 12.0,
                'ultima_compra' => Carbon::now()->subDays(random_int(1, 30))->format('d/m/Y'),
            ],
            [
                'nome' => 'Fio de Sutura',
                'categoria' => 'Instrumentais',
                'centro' => random_int(0, 50),
                'norte' => random_int(0, 50),
                'sul' => random_int(0, 50),
                'minimo' => 40,
                'valor_unitario' => 5.0,
                'ultima_compra' => Carbon::now()->subDays(random_int(1, 30))->format('d/m/Y'),
            ],
            [
                'nome' => 'EPI - Protetor Facial',
                'categoria' => 'EPIs',
                'centro' => random_int(0, 20),
                'norte' => random_int(0, 20),
                'sul' => random_int(0, 20),
                'minimo' => 15,
                'valor_unitario' => 25.0,
                'ultima_compra' => Carbon::now()->subDays(random_int(1, 30))->format('d/m/Y'),
            ],
        ])->map(function ($p) {
            $p['total'] = $p['centro'] + $p['norte'] + $p['sul'];
            $p['status'] = $p['total'] < $p['minimo'] ? 'Baixo' : 'Normal';
            $p['valor_total'] = $p['valor_unitario'] * $p['total'];
            return $p;
        });

        $valorEstoque = $produtos->sum('valor_total');
        $ultimaAtualizacao = Carbon::now()->format('d/m/Y H:i');
        $itensCriticos = $produtos->where('status', 'Baixo');

        $consumoRecente = [
            [
                'nome' => 'Luvas de Procedimento',
                'centro' => random_int(0, 20),
                'norte' => random_int(0, 20),
                'sul' => random_int(0, 20),
                'responsavel' => 'Dra. Ana Silva',
            ],
            [
                'nome' => 'Anestésico',
                'centro' => random_int(0, 10),
                'norte' => random_int(0, 10),
                'sul' => random_int(0, 10),
                'responsavel' => 'Diversos',
            ],
        ];

        return view('estoque.index', [
            'clinics' => $clinics,
            'produtos' => $produtos,
            'valorEstoque' => $valorEstoque,
            'ultimaAtualizacao' => $ultimaAtualizacao,
            'itensCriticos' => $itensCriticos,
            'consumoRecente' => $consumoRecente,
        ]);
    }
}
