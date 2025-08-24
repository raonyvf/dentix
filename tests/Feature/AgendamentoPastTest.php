<?php

namespace App\Models {
    class Agendamento {
        public static function create(array $data) {}
    }
}

namespace Illuminate\Http {
    class Request {
        public function __construct(private array $data = []) {}
        public function validate(array $rules) { return $this->data; }
    }
}

namespace {
    require_once __DIR__ . '/../Unit/stubs.php';
    require_once __DIR__ . '/../../app/Http/Controllers/Admin/AgendamentoController.php';

    use PHPUnit\Framework\TestCase;
    use App\Http\Controllers\Admin\AgendamentoController;
    use Carbon\Carbon;

    if (!function_exists('app')) {
        function app($key = null) {
            if ($key === null) {
                return new class {
                    public function bound($name) { return $name === 'clinic_id'; }
                };
            }
            return $key === 'clinic_id' ? 1 : null;
        }
    }

    if (!function_exists('response')) {
        function response() {
            return new class {
                public function json($data, $status = 200) { return $data + ['status' => $status]; }
            };
        }
    }

    class AgendamentoPastTest extends TestCase {
        public function test_store_rejects_past_schedule() {
            \Carbon\Carbon::$now = '2024-05-10 10:00';
            $controller = new AgendamentoController();
            $req = new \Illuminate\Http\Request([
                'paciente_id' => 1,
                'data' => '2024-05-09',
                'hora_inicio' => '09:00',
                'hora_fim' => '10:00',
                'status' => 'pendente',
            ]);
            $res = $controller->store($req);
            $this->assertSame(false, $res['success']);
            $this->assertSame(422, $res['status']);
        }
    }

    $test = new AgendamentoPastTest();
    $test->test_store_rejects_past_schedule();
    echo "Tests passed\n";
}
