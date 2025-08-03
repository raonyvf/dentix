<?php

use PHPUnit\Framework\TestCase;
use Illuminate\Container\Container;
use Illuminate\Http\Request;
use App\Http\Controllers\Admin\AgendamentoController;

class AgendamentoControllerTest extends TestCase
{
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
}
