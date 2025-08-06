<?php

use PHPUnit\Framework\TestCase;
use Illuminate\Container\Container;
use Illuminate\Http\Request;
use App\Http\Controllers\Admin\AgendamentoController;
use Mockery;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;

class AgendamentoControllerTest extends TestCase
{
    use MockeryPHPUnitIntegration;
    public function test_store_without_clinic_returns_error()
    {
        Container::setInstance(new Container());

        $controller = new AgendamentoController();
        $request = Request::create('/admin/agendamentos', 'POST');

        $response = $controller->store($request);
        $data = $response->getData(true);

        $this->assertSame(400, $response->status());
        $this->assertFalse($data['success']);
        $this->assertSame('Clínica não selecionada.', $data['message']);
        $this->assertSame('/admin/clinicas', $data['redirect']);
    }

    public function test_professionals_for_date_detects_dentist_variations()
    {
        $clinicId = 1;
        $date = '2024-05-20';

        $collection = collect([
            (object) ['profissional' => (object) [
                'id' => 1,
                'funcao' => 'Dentista(a)',
                'cargo' => null,
                'user' => null,
                'pessoa' => (object) ['primeiro_nome' => 'Ana', 'sexo' => 'Feminino'],
            ]],
            (object) ['profissional' => (object) [
                'id' => 2,
                'funcao' => 'Dentista Clínico',
                'cargo' => null,
                'user' => null,
                'pessoa' => (object) ['primeiro_nome' => 'Bruno', 'sexo' => 'Masculino'],
            ]],
            (object) ['profissional' => (object) [
                'id' => 3,
                'funcao' => null,
                'cargo' => 'Dentista(a)',
                'user' => null,
                'pessoa' => (object) ['primeiro_nome' => 'Clara', 'sexo' => 'Feminino'],
            ]],
            (object) ['profissional' => (object) [
                'id' => 4,
                'funcao' => null,
                'cargo' => 'Dentista Clínico',
                'user' => null,
                'pessoa' => (object) ['primeiro_nome' => 'Diego', 'sexo' => 'Masculino'],
            ]],
            (object) ['profissional' => (object) [
                'id' => 5,
                'funcao' => 'Auxiliar',
                'cargo' => 'Recepcionista',
                'user' => null,
                'pessoa' => (object) ['primeiro_nome' => 'Eva', 'sexo' => 'Feminino'],
            ]],
        ]);

        $mock = Mockery::mock('alias:App\Models\EscalaTrabalho');
        $mock->shouldReceive('with')->andReturnSelf();
        $mock->shouldReceive('where')->andReturnSelf();
        $mock->shouldReceive('get')->andReturn($collection);

        $controller = new AgendamentoController();
        $reflection = new \ReflectionClass($controller);
        $method = $reflection->getMethod('professionalsForDate');
        $method->setAccessible(true);

        $result = $method->invoke($controller, $clinicId, $date);
        $ids = array_column($result, 'id');
        sort($ids);

        $this->assertSame([1, 2, 3, 4], $ids);
    }
}
