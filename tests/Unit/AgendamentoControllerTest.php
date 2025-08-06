<?php

require_once __DIR__.'/stubs.php';
require_once __DIR__ . '/../../app/Http/Controllers/Admin/AgendamentoController.php';

use PHPUnit\Framework\TestCase;
use App\Http\Controllers\Admin\AgendamentoController;

class AgendamentoControllerTest extends TestCase
{
    public function test_professionals_for_date_returns_all_scheduled_professionals()
    {
        $clinicId = 1;
        $date = '2024-05-20';

        $escalas = collect([
            (object) ['profissional_id' => 1],
            (object) ['profissional_id' => 2],
        ]);

        $profissionais = collect([
            (object) [
                'id' => 1,
                'pessoa' => (object) ['primeiro_nome' => 'Ana', 'sexo' => 'Feminino'],
            ],
            (object) [
                'id' => 2,
                'pessoa' => (object) ['primeiro_nome' => 'Bruno', 'sexo' => 'Masculino'],
            ],
        ]);

        \App\Models\EscalaTrabalho::setCollection($escalas);
        \App\Models\Profissional::setCollection($profissionais);

        $controller = new AgendamentoController();
        $reflection = new \ReflectionClass($controller);
        $method = $reflection->getMethod('professionalsForDate');
        $method->setAccessible(true);

        $result = $method->invoke($controller, $clinicId, $date);
        $ids = array_column($result, 'id');
        sort($ids);

        $this->assertSame([1, 2], $ids);
    }
}

$test = new AgendamentoControllerTest();
$test->test_professionals_for_date_returns_all_scheduled_professionals();
echo "Test passed\n";