<?php

namespace App\Models {
    class Agendamento
    {
        public static array $records = [];
        public static function with($relations)
        {
            return new self;
        }
        private array $filters = [];
        public function where($field, $operator = null, $value = null)
        {
            if ($value === null) {
                $value = $operator;
                $operator = '=';
            }
            $this->filters[] = [$field, $operator, $value];
            return $this;
        }
        public function whereDate($field, $value)
        {
            $this->filters[] = [$field, '=', $value];
            return $this;
        }
        public function whereIn($field, $values)
        {
            $this->filters[] = [$field, 'in', $values];
            return $this;
        }
        public function get()
        {
            $results = array_filter(self::$records, function ($rec) {
                foreach ($this->filters as [$field, $op, $value]) {
                    $recordValue = $rec[$field] ?? null;
                    if ($op === '=' && $recordValue != $value) return false;
                    if ($op === '!=' && $recordValue == $value) return false;
                    if ($op === 'in' && !in_array($recordValue, $value)) return false;
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

    class ConsultasDiaTest extends TestCase
    {
        public function test_excludes_waitlist_from_consultas()
        {
            \App\Models\Agendamento::$records = [
                [
                    'clinica_id' => 1,
                    'data' => '2025-08-07',
                    'hora_inicio' => '09:00',
                    'status' => 'lista_espera',
                    'paciente' => (object) ['pessoa' => (object) ['primeiro_nome' => 'A', 'ultimo_nome' => 'LE']],
                    'profissional' => (object) ['pessoa' => (object) ['primeiro_nome' => 'Pro', 'ultimo_nome' => 'A']],
                ],
                [
                    'clinica_id' => 1,
                    'data' => '2025-08-07',
                    'hora_inicio' => '10:00',
                    'status' => 'pendente',
                    'paciente' => (object) ['pessoa' => (object) ['primeiro_nome' => 'B', 'ultimo_nome' => 'P']],
                    'profissional' => (object) ['pessoa' => (object) ['primeiro_nome' => 'Pro', 'ultimo_nome' => 'B']],
                ],
                [
                    'clinica_id' => 1,
                    'data' => '2025-08-07',
                    'hora_inicio' => '11:00',
                    'status' => 'confirmado',
                    'paciente' => (object) ['pessoa' => (object) ['primeiro_nome' => 'C', 'ultimo_nome' => 'C']],
                    'profissional' => (object) ['pessoa' => (object) ['primeiro_nome' => 'Pro', 'ultimo_nome' => 'C']],
                ],
                [
                    'clinica_id' => 1,
                    'data' => '2025-08-07',
                    'hora_inicio' => '12:00',
                    'status' => 'cancelado',
                    'paciente' => (object) ['pessoa' => (object) ['primeiro_nome' => 'D', 'ultimo_nome' => 'D']],
                    'profissional' => (object) ['pessoa' => (object) ['primeiro_nome' => 'Pro', 'ultimo_nome' => 'D']],
                ],
                [
                    'clinica_id' => 1,
                    'data' => '2025-08-07',
                    'hora_inicio' => '13:00',
                    'status' => 'faltou',
                    'paciente' => (object) ['pessoa' => (object) ['primeiro_nome' => 'E', 'ultimo_nome' => 'F']],
                    'profissional' => (object) ['pessoa' => (object) ['primeiro_nome' => 'Pro', 'ultimo_nome' => 'E']],
                ],
            ];

            $controller = new AgendamentoController();
            $req = new \Illuminate\Http\Request(['date' => '2025-08-07']);
            $res = $controller->consultasDia($req);
            $consultas = $res['consultas']->toArray();
            $statuses = array_map(fn($c) => $c['status'], $consultas);
            sort($statuses);
            $this->assertSame(['cancelado', 'confirmado', 'pendente'], $statuses);
        }
    }

    $test = new ConsultasDiaTest();
    $test->test_excludes_waitlist_from_consultas();
    echo "Tests passed\n";
}
