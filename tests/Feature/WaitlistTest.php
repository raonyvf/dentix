<?php

namespace App\Models {
    class Agendamento
    {
        public static array $records = [];
        public static array $patients = [];

        public static function create(array $data)
        {
            $data['id'] = count(self::$records) + 1;
            $data['paciente'] = self::$patients[$data['paciente_id']] ?? null;
            self::$records[] = $data;
        }

        public static function with($relations)
        {
            return new self;
        }

        private array $filters = [];

        public function where($field, $operator = null, $value = null)
        {
            if ($value === null) {
                $value = $operator;
            }
            $this->filters[$field] = $value;
            return $this;
        }

        public function whereDate($field, $value)
        {
            $this->filters[$field] = $value;
            return $this;
        }

        public function get()
        {
            $results = array_filter(self::$records, function ($rec) {
                foreach ($this->filters as $k => $v) {
                    if (($rec[$k] ?? null) != $v) {
                        return false;
                    }
                }
                return true;
            });
            return collect(array_map(fn($r) => (object) $r, $results));
        }
    }
}

namespace Illuminate\Http {
    class Request
    {
        public function __construct(private array $data = []) {}
        public function query($key, $default = null)
        {
            return $this->data[$key] ?? $default;
        }
        public function validate(array $rules, array $messages = [])
        {
            return $this->data;
        }
    }
}

namespace {
    require_once __DIR__ . '/../Unit/stubs.php';
    require_once __DIR__ . '/../../app/Http/Controllers/Admin/AgendamentoController.php';

    use PHPUnit\Framework\TestCase;
    use App\Http\Controllers\Admin\AgendamentoController;

    if (!function_exists('app')) {
        function app($key = null)
        {
            if ($key === null) {
                return new class {
                    public function bound($name)
                    {
                        return $name === 'clinic_id';
                    }
                };
            }
            return $key === 'clinic_id' ? 1 : null;
        }
    }

    if (!function_exists('response')) {
        function response()
        {
            return new class {
                public function json($data)
                {
                    return $data;
                }
            };
        }
    }

    if (!function_exists('optional')) {
        function optional($value)
        {
            return $value ? $value : new class {
                public function __get($name)
                {
                    return null;
                }
            };
        }
    }

    class WaitlistTest extends TestCase
    {
        public function test_store_and_waitlist_flow()
        {
            \App\Models\Agendamento::$records = [];
            \App\Models\Agendamento::$patients = [
                10 => (object) ['pessoa' => (object) ['primeiro_nome' => 'Ana', 'ultimo_nome' => 'Silva']],
            ];

            $controller = new AgendamentoController();
            $storeReq = new \Illuminate\Http\Request([
                'paciente_id' => 10,
                'data' => '2025-08-07',
                'status' => 'lista_espera',
                'contato' => '1199999999',
                'observacao' => 'Checar radiografia',
            ]);
            $res = $controller->store($storeReq);
            $this->assertSame(['success' => true], $res);

            $waitReq = new \Illuminate\Http\Request(['date' => '2025-08-07']);
            $waitRes = $controller->waitlist($waitReq);
            $items = $waitRes['waitlist']->toArray();
            $this->assertSame(1, count($items));
            $this->assertSame('Ana Silva', $items[0]['paciente']);
            $this->assertSame('1199999999', $items[0]['contato']);
            $this->assertSame('Checar radiografia', $items[0]['observacao']);
        }
    }

    $test = new WaitlistTest();
    $test->test_store_and_waitlist_flow();
    echo "Tests passed\n";
}
