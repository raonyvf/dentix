<?php

namespace App\Models {
    use Normalizer;

    class PatientQuery
    {
        private array $base;
        private array $results;

        public function __construct(array $patients)
        {
            $this->base = $patients;
            $this->results = $patients;
        }

        public function join($table, $first, $operator, $second): self
        {
            return $this;
        }

        public function when($value, $callback): self
        {
            if ($value) {
                $callback($this);
            }
            return $this;
        }

        public function where($callback): self
        {
            $callback($this);
            return $this;
        }

        public function whereRaw($sql, $bindings): self
        {
            $this->results = $this->filter($sql, $bindings);
            return $this;
        }

        public function orWhereRaw($sql, $bindings): self
        {
            $matches = $this->filter($sql, $bindings);
            $this->results = array_values(array_unique(array_merge($this->results, $matches), SORT_REGULAR));
            return $this;
        }

        public function orWhere($field, $value): self
        {
            if ($field === 'pacientes.id') {
                $matches = array_filter($this->base, fn($p) => $p->id == $value);
                $this->results = array_values(array_unique(array_merge($this->results, $matches), SORT_REGULAR));
            }
            return $this;
        }

        public function orderByRaw($sql, $bindings): self
        {
            return $this;
        }

        public function limit($limit): self
        {
            return $this;
        }

        public function get($columns)
        {
            return collect($this->results);
        }

        private function filter($sql, $bindings): array
        {
            $pattern = $bindings[0];
            $term = str_replace('%', '', $pattern);
            $matchStarts = !str_starts_with($pattern, '%');

            if (str_contains($sql, 'concat')) {
                return array_filter($this->base, function ($p) use ($term, $matchStarts) {
                    $name = trim($p->primeiro_nome . ' ' . ($p->nome_meio ? $p->nome_meio . ' ' : '') . $p->ultimo_nome);
                    $normName = self::normalizeValue($name);
                    return $matchStarts ? str_starts_with($normName, $term) : str_contains($normName, $term);
                });
            }

            if (str_contains($sql, 'pessoas.email')) {
                return array_filter($this->base, function ($p) use ($term) {
                    $normEmail = self::normalizeValue($p->email ?? '');
                    return str_contains($normEmail, $term);
                });
            }

            return [];
        }

        private static function normalizeValue(string $value): string
        {
            $normalized = Normalizer::normalize($value, Normalizer::FORM_D);
            $withoutAccents = preg_replace('/\pM/u', '', $normalized);
            return mb_strtolower($withoutAccents);
        }
    }

    class Patient
    {
        public static array $collection = [];

        public static function setCollection(array $patients): void
        {
            self::$collection = $patients;
        }

        public static function query(): PatientQuery
        {
            return new PatientQuery(self::$collection);
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

        public function get($key, $default = null)
        {
            return $this->query[$key] ?? $default;
        }
    }
}

namespace {
    require_once __DIR__.'/../Unit/stubs.php';
    require_once __DIR__.'/../../app/Http/Controllers/Admin/PatientController.php';

    use PHPUnit\Framework\TestCase;
    use App\Http\Controllers\Admin\PatientController;

    if (! function_exists('response')) {
        function response()
        {
            return new class {
                public function json($data)
                {
                    return $data instanceof \Collection ? $data->toArray() : $data;
                }
            };
        }
    }

    class PatientSearchTest extends TestCase
    {
        public function test_search_returns_patient_by_id(): void
        {
            $patients = [
                (object) ['id' => 1, 'primeiro_nome' => 'Alice', 'nome_meio' => null, 'ultimo_nome' => 'Smith', 'phone' => '', 'whatsapp' => '', 'cpf' => '', 'email' => null],
                (object) ['id' => 2, 'primeiro_nome' => 'Bob', 'nome_meio' => null, 'ultimo_nome' => 'Jones', 'phone' => '', 'whatsapp' => '', 'cpf' => '', 'email' => null],
            ];
            \App\Models\Patient::setCollection($patients);

            $controller = new PatientController();
            $request = new \Illuminate\Http\Request(['q' => '2']);
            $result = $controller->search($request);

            $this->assertSame([
                ['id' => 2, 'name' => 'Bob Jones', 'phone' => '', 'cpf' => ''],
            ], $result);
        }

        public function test_accented_name_matches(): void
        {
            $patients = [
                (object) ['id' => 1, 'primeiro_nome' => 'Matéus', 'nome_meio' => null, 'ultimo_nome' => 'Silva', 'phone' => '', 'whatsapp' => '', 'cpf' => '', 'email' => null],
            ];
            \App\Models\Patient::setCollection($patients);

            $controller = new PatientController();
            $request = new \Illuminate\Http\Request(['q' => 'Mateus']);
            $result = $controller->search($request);

            $this->assertSame([
                ['id' => 1, 'name' => 'Matéus Silva', 'phone' => '', 'cpf' => ''],
            ], $result);
        }
    }

    $test = new PatientSearchTest();
    $test->test_search_returns_patient_by_id();
    $test->test_accented_name_matches();
    echo "Test passed\n";
}

