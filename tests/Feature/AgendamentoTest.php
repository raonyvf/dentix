<?php

namespace App\Models {
    class Agendamento
    {
        public static function with($relations)
        {
            return new self;
        }

        public function where($field, $value)
        {
            return $this;
        }

        public function whereDate($field, $value)
        {
            return $this;
        }

        public function whereIn($field, $values)
        {
            return $this;
        }

        public function get()
        {
            return collect([]);
        }
    }
}

namespace Illuminate\Http {
    class Request
    {
        private array $query;

        public function __construct(array $query = [])
        {
            $this->query = $query;
        }

        public function query($key, $default = null)
        {
            return $this->query[$key] ?? $default;
        }
    }
}

namespace {
    require_once __DIR__ . '/../Unit/stubs.php';
    require_once __DIR__ . '/../../app/Http/Controllers/Admin/AgendamentoController.php';

    use PHPUnit\Framework\TestCase;
    use App\Http\Controllers\Admin\AgendamentoController;

    if (! function_exists('app')) {
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

    if (! function_exists('response')) {
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

    class AgendamentoTest extends TestCase
    {
        public function test_professionals_route_returns_all_scheduled_professionals()
        {
            $escalas = collect([
                (object) ['profissional_id' => 1, 'clinica_id' => 1, 'semana' => '2025-08-04', 'dia_semana' => 4],
                (object) ['profissional_id' => 2, 'clinica_id' => 1, 'semana' => '2025-08-04', 'dia_semana' => 4],
            ]);
            \App\Models\EscalaTrabalho::setCollection($escalas);

            $profissionais = collect([
                (object) ['id' => 1, 'pessoa' => (object) ['primeiro_nome' => 'Dentista Um', 'sexo' => 'Feminino']],
                (object) ['id' => 2, 'pessoa' => (object) ['primeiro_nome' => 'Dentista Dois', 'sexo' => 'Masculino']],
            ]);
            \App\Models\Profissional::setCollection($profissionais);

            $controller = new AgendamentoController();
            $request = new \Illuminate\Http\Request(['date' => '2025-08-07']);
            $result = $controller->professionals($request);

            $ids = array_column($result['professionals'], 'id');
            sort($ids);

            $this->assertSame([1, 2], $ids);
        }

        public function test_professionals_route_excludes_unscheduled_professionals()
        {
            $escalas = collect([
                (object) ['profissional_id' => 1, 'clinica_id' => 1, 'semana' => '2025-08-04', 'dia_semana' => 4],
            ]);
            \App\Models\EscalaTrabalho::setCollection($escalas);

            $profissionais = collect([
                (object) ['id' => 1, 'pessoa' => (object) ['primeiro_nome' => 'Dentista Um', 'sexo' => 'Feminino']],
                (object) ['id' => 2, 'pessoa' => (object) ['primeiro_nome' => 'Dentista Dois', 'sexo' => 'Masculino']],
            ]);
            \App\Models\Profissional::setCollection($profissionais);

            $controller = new AgendamentoController();
            $request = new \Illuminate\Http\Request(['date' => '2025-08-07']);
            $result = $controller->professionals($request);

            $ids = array_column($result['professionals'], 'id');
            sort($ids);

            $this->assertSame([1], $ids);
        }
    }

    $test = new AgendamentoTest();
    $test->test_professionals_route_returns_all_scheduled_professionals();
    $test->test_professionals_route_excludes_unscheduled_professionals();
    echo "Tests passed\n";
}

