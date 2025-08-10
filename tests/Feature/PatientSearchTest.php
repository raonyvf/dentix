<?php

namespace App\Models {
    use Normalizer;

    class PatientQuery
    {
        private array $base;
        private array $results;
        public array $usedIndexes = [];

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

        public function when($value, $callback): self
        {
            if ($value) {
                $callback($this);
            }
            return $this;
        }

        public function where($field, $operator = null, $value = null): self
        {
            if (is_callable($field)) {
                $field($this);
                return $this;
            }

            if ($field === 'pessoas.normalized_name' && $operator === 'like') {
                $term = trim($value, '%');
                $matchStarts = !str_starts_with($value, '%');
                $matches = array_filter($this->base, function ($p) use ($term, $matchStarts) {
                    $name = $p->normalized_name;
                    return $matchStarts ? str_starts_with($name, $term) : str_contains($name, $term);
                });
                $this->results = $matches;
                $this->usedIndexes[] = 'pessoas_normalized_name_index';
            }

            return $this;
        }

        public function orWhere($field, $operator = null, $value = null): self
        {
            if ($field === 'pessoas.normalized_name' && $operator === 'like') {
                $term = trim($value, '%');
                $matchStarts = !str_starts_with($value, '%');
                $matches = array_filter($this->base, function ($p) use ($term, $matchStarts) {
                    $name = $p->normalized_name;
                    return $matchStarts ? str_starts_with($name, $term) : str_contains($name, $term);
                });
                $this->results = array_values(array_unique(array_merge($this->results, $matches), SORT_REGULAR));
                $this->usedIndexes[] = 'pessoas_normalized_name_index';
            } elseif ($field === 'pessoas.digits_phone' && $operator === 'like') {
                $term = trim($value, '%');
                $matches = array_filter($this->base, function ($p) use ($term) {
                    return str_contains($p->digits_phone, $term);
                });
                $this->results = array_values(array_unique(array_merge($this->results, $matches), SORT_REGULAR));
                $this->usedIndexes[] = 'pessoas_digits_phone_index';
            } elseif ($field === 'pessoas.digits_whatsapp' && $operator === 'like') {
                $term = trim($value, '%');
                $matches = array_filter($this->base, function ($p) use ($term) {
                    return str_contains($p->digits_whatsapp, $term);
                });
                $this->results = array_values(array_unique(array_merge($this->results, $matches), SORT_REGULAR));
                $this->usedIndexes[] = 'pessoas_digits_whatsapp_index';
            } elseif ($field === 'pessoas.digits_cpf' && $operator === 'like') {
                $term = trim($value, '%');
                $matches = array_filter($this->base, function ($p) use ($term) {
                    return str_contains($p->digits_cpf, $term);
                });
                $this->results = array_values(array_unique(array_merge($this->results, $matches), SORT_REGULAR));
                $this->usedIndexes[] = 'pessoas_digits_cpf_index';
            } elseif ($field === 'pacientes.id') {
                $matches = array_filter($this->base, fn($p) => $p->id == $operator);
                $this->results = array_values(array_unique(array_merge($this->results, $matches), SORT_REGULAR));
            }

            return $this;
        }

        public function orWhereRaw($sql, $bindings): self
        {
            $matches = [];
            if (str_contains($sql, 'pessoas.email')) {
                $pattern = $bindings[0];
                $term = str_replace('%', '', $pattern);
                $matches = array_filter($this->base, function ($p) use ($term) {
                    $email = self::normalizeValue($p->email ?? '');
                    return str_contains($email, $term);
                });
            }
            $this->results = array_values(array_unique(array_merge($this->results, $matches), SORT_REGULAR));
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
        public static ?PatientQuery $lastQuery = null;

        public static function setCollection(array $patients): void
        {
            self::$collection = $patients;
        }

        public static function query(): PatientQuery
        {
            self::$lastQuery = new PatientQuery(self::$collection);
            return self::$lastQuery;
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

namespace Illuminate\Support\Facades {
    class DB
    {
        public static function getDriverName()
        {
            return 'sqlite';
        }

        public static function statement($sql)
        {
            // no-op for tests
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
        private function makePatient(array $attributes)
        {
            $base = [
                'id' => 1,
                'primeiro_nome' => 'John',
                'nome_meio' => null,
                'ultimo_nome' => 'Doe',
                'phone' => '',
                'whatsapp' => '',
                'cpf' => '',
                'email' => null,
            ];
            $p = (object) array_merge($base, $attributes);
            $name = trim($p->primeiro_nome . ' ' . ($p->nome_meio ? $p->nome_meio . ' ' : '') . $p->ultimo_nome);
            $p->normalized_name = Normalizer::normalize($name, Normalizer::FORM_D);
            $p->normalized_name = preg_replace('/\pM/u', '', $p->normalized_name);
            $p->normalized_name = mb_strtolower($p->normalized_name);
            $p->digits_phone = preg_replace('/\D/', '', $p->phone);
            $p->digits_whatsapp = preg_replace('/\D/', '', $p->whatsapp);
            $p->digits_cpf = preg_replace('/\D/', '', $p->cpf);
            return $p;
        }

        public function test_search_by_name_uses_index(): void
        {
            $patients = [
                $this->makePatient(['id' => 1, 'primeiro_nome' => 'Matéus', 'ultimo_nome' => 'Silva']),
            ];
            \App\Models\Patient::setCollection($patients);

            $controller = new PatientController();
            $request = new \Illuminate\Http\Request(['q' => 'Mateus']);
            $result = $controller->search($request);

            $this->assertSame([
                ['id' => 1, 'name' => 'Matéus Silva', 'phone' => '', 'cpf' => ''],
            ], $result);

            $this->assertSame(true, in_array('pessoas_normalized_name_index', \App\Models\Patient::$lastQuery->usedIndexes));
        }

        public function test_search_by_phone_uses_index(): void
        {
            $patients = [
                $this->makePatient(['id' => 1, 'primeiro_nome' => 'Ana', 'ultimo_nome' => 'Silva', 'phone' => '(11) 91234-5678']),
            ];
            \App\Models\Patient::setCollection($patients);

            $controller = new PatientController();
            $request = new \Illuminate\Http\Request(['q' => '11912345678']);
            $result = $controller->search($request);

            $this->assertSame([
                ['id' => 1, 'name' => 'Ana Silva', 'phone' => '(11) 91234-5678', 'cpf' => ''],
            ], $result);

            $this->assertSame(true, in_array('pessoas_digits_phone_index', \App\Models\Patient::$lastQuery->usedIndexes));
        }

        public function test_search_by_cpf_uses_index(): void
        {
            $patients = [
                $this->makePatient(['id' => 1, 'primeiro_nome' => 'Carlos', 'ultimo_nome' => 'Ferreira', 'cpf' => '123.456.789-00']),
            ];
            \App\Models\Patient::setCollection($patients);

            $controller = new PatientController();
            $request = new \Illuminate\Http\Request(['q' => '12345678900']);
            $result = $controller->search($request);

            $this->assertSame([
                ['id' => 1, 'name' => 'Carlos Ferreira', 'phone' => '', 'cpf' => '123.456.789-00'],
            ], $result);

            $this->assertSame(true, in_array('pessoas_digits_cpf_index', \App\Models\Patient::$lastQuery->usedIndexes));
        }
    }

    $test = new PatientSearchTest();
    $test->test_search_by_name_uses_index();
    $test->test_search_by_phone_uses_index();
    $test->test_search_by_cpf_uses_index();
    echo "Test passed\n";
}
