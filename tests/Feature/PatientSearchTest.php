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

        public function select($columns): self
        {
            return $this;
        }

        public function selectRaw($expression): self
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

        public function orWhere($field, $operator = null, $value = null): self
        {
            if ($field === 'pacientes.id') {
                $matches = array_filter($this->base, fn($p) => $p->id == $operator);
                $this->results = array_values(array_unique(array_merge($this->results, $matches), SORT_REGULAR));
            } elseif ($field === 'digits_phone' && $operator === 'like') {
                $term = trim($value, '%');
                $matches = array_filter($this->base, function ($p) use ($term) {
                    $digits = self::digits(($p->phone ?? '') . ($p->whatsapp ?? ''));
                    return str_contains($digits, $term);
                });
                $this->results = array_values(array_unique(array_merge($this->results, $matches), SORT_REGULAR));
            } elseif ($field === 'digits_cpf' && $operator === 'like') {
                $term = trim($value, '%');
                $matches = array_filter($this->base, function ($p) use ($term) {
                    $digits = self::digits($p->cpf ?? '');
                    return str_contains($digits, $term);
                });
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

        public function get($columns = ['*'])
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

        private static function digits(string $value): string
        {
            return preg_replace('/\D/', '', $value);
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

        public function test_phone_search_matches_varied_formats(): void
        {
            $patients = [
                (object) ['id' => 1, 'primeiro_nome' => 'Ana', 'nome_meio' => null, 'ultimo_nome' => 'Silva', 'phone' => '(11) 91234-5678', 'whatsapp' => '', 'cpf' => '', 'email' => null],
                (object) ['id' => 2, 'primeiro_nome' => 'Bia', 'nome_meio' => null, 'ultimo_nome' => 'Smith', 'phone' => '21987654321', 'whatsapp' => '', 'cpf' => '', 'email' => null],
            ];
            \App\Models\Patient::setCollection($patients);

            $controller = new PatientController();

            $request = new \Illuminate\Http\Request(['q' => '11912345678']);
            $result = $controller->search($request);

            $this->assertSame([
                ['id' => 1, 'name' => 'Ana Silva', 'phone' => '(11) 91234-5678', 'cpf' => ''],
            ], $result);

            $request = new \Illuminate\Http\Request(['q' => '(21) 98765-4321']);
            $result = $controller->search($request);

            $this->assertSame([
                ['id' => 2, 'name' => 'Bia Smith', 'phone' => '21987654321', 'cpf' => ''],
            ], $result);
        }

        public function test_cpf_search_matches_varied_formats(): void
        {
            $patients = [
                (object) ['id' => 1, 'primeiro_nome' => 'Carlos', 'nome_meio' => null, 'ultimo_nome' => 'Ferreira', 'phone' => '', 'whatsapp' => '', 'cpf' => '123.456.789-00', 'email' => null],
                (object) ['id' => 2, 'primeiro_nome' => 'Daniela', 'nome_meio' => null, 'ultimo_nome' => 'Costa', 'phone' => '', 'whatsapp' => '', 'cpf' => '98765432100', 'email' => null],
            ];
            \App\Models\Patient::setCollection($patients);

            $controller = new PatientController();

            $request = new \Illuminate\Http\Request(['q' => '12345678900']);
            $result = $controller->search($request);

            $this->assertSame([
                ['id' => 1, 'name' => 'Carlos Ferreira', 'phone' => '', 'cpf' => '123.456.789-00'],
            ], $result);

            $request = new \Illuminate\Http\Request(['q' => '987.654.321-00']);
            $result = $controller->search($request);

            $this->assertSame([
                ['id' => 2, 'name' => 'Daniela Costa', 'phone' => '', 'cpf' => '98765432100'],
            ], $result);
        }
    }

    $test = new PatientSearchTest();
    $test->test_search_returns_patient_by_id();
    $test->test_accented_name_matches();
    $test->test_phone_search_matches_varied_formats();
    $test->test_cpf_search_matches_varied_formats();
    echo "Test passed\n";
}

