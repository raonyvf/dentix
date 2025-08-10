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

        public function whereHas($relation, $callback): self
        {
            $callback($this);
            return $this;
        }

        public function where($field, $operator = null, $value = null): self
        {
            if (is_callable($field)) {
                $field($this);
                return $this;
            }

            if ($field === 'normalized_name' && $operator === 'like') {
                $term = trim($value, '%');
                $matchStarts = !str_starts_with($value, '%');
                $matches = array_filter($this->base, function ($p) use ($term, $matchStarts) {
                    $name = $p->pessoa->normalized_name;
                    return $matchStarts ? str_starts_with($name, $term) : str_contains($name, $term);
                });
                $this->results = $matches;
                $this->usedIndexes[] = 'pessoas_normalized_name_index';
            }

            return $this;
        }

        public function orWhere($field, $operator = null, $value = null): self
        {
            if ($field === 'email' && $operator === 'like') {
                $term = trim($value, '%');
                $matches = array_filter($this->base, fn($p) => str_contains($p->pessoa->email ?? '', $term));
                $this->results = array_values(array_unique(array_merge($this->results, $matches), SORT_REGULAR));
            } elseif ($field === 'digits_phone' && $operator === 'like') {
                $term = trim($value, '%');
                $matches = array_filter($this->base, fn($p) => str_contains($p->pessoa->digits_phone, $term));
                $this->results = array_values(array_unique(array_merge($this->results, $matches), SORT_REGULAR));
                $this->usedIndexes[] = 'pessoas_digits_phone_index';
            } elseif ($field === 'digits_whatsapp' && $operator === 'like') {
                $term = trim($value, '%');
                $matches = array_filter($this->base, fn($p) => str_contains($p->pessoa->digits_whatsapp, $term));
                $this->results = array_values(array_unique(array_merge($this->results, $matches), SORT_REGULAR));
                $this->usedIndexes[] = 'pessoas_digits_whatsapp_index';
            } elseif ($field === 'digits_cpf' && $operator === 'like') {
                $term = trim($value, '%');
                $matches = array_filter($this->base, fn($p) => str_contains($p->pessoa->digits_cpf, $term));
                $this->results = array_values(array_unique(array_merge($this->results, $matches), SORT_REGULAR));
                $this->usedIndexes[] = 'pessoas_digits_cpf_index';
            }

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
    }

    class Patient
    {
        public static array $collection = [];
        public static ?PatientQuery $lastQuery = null;

        public static function setCollection(array $patients): void
        {
            self::$collection = $patients;
        }

        public static function with($relation): PatientQuery
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

        public function query($key = null, $default = null)
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

namespace Illuminate\Support {
    class Str
    {
        public static function of($value)
        {
            return new class($value) {
                private string $value;
                public function __construct($value)
                {
                    $this->value = $value;
                }

                public function ascii()
                {
                    $this->value = iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $this->value);
                    return $this;
                }

                public function lower()
                {
                    $this->value = mb_strtolower($this->value);
                    return $this;
                }

                public function __toString()
                {
                    return $this->value;
                }
            };
        }
    }
}

namespace {
    require_once __DIR__.'/../Unit/stubs.php';
    require_once __DIR__.'/../../app/Http/Controllers/Admin/PatientController.php';

    use PHPUnit\Framework\TestCase;
    use App\Http\Controllers\Admin\PatientController;
    use App\Models\Patient;

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
            $defaults = [
                'id' => 1,
                'primeiro_nome' => 'John',
                'nome_meio' => null,
                'ultimo_nome' => 'Doe',
                'phone' => '',
                'whatsapp' => '',
                'cpf' => '',
                'email' => null,
            ];
            $data = array_merge($defaults, $attributes);
            $pessoa = (object) [
                'primeiro_nome' => $data['primeiro_nome'],
                'nome_meio' => $data['nome_meio'],
                'ultimo_nome' => $data['ultimo_nome'],
                'phone' => $data['phone'],
                'whatsapp' => $data['whatsapp'],
                'cpf' => $data['cpf'],
                'email' => $data['email'],
            ];
            $name = trim($pessoa->primeiro_nome . ' ' . ($pessoa->nome_meio ? $pessoa->nome_meio . ' ' : '') . $pessoa->ultimo_nome);
            $pessoa->full_name = $name;
            $normalized = Normalizer::normalize($name, Normalizer::FORM_D);
            $normalized = preg_replace('/\pM/u', '', $normalized);
            $pessoa->normalized_name = mb_strtolower($normalized);
            $pessoa->digits_phone = preg_replace('/\D/', '', $pessoa->phone);
            $pessoa->digits_whatsapp = preg_replace('/\D/', '', $pessoa->whatsapp);
            $pessoa->digits_cpf = preg_replace('/\D/', '', $pessoa->cpf);
            $patient = (object) ['id' => $data['id'], 'pessoa' => $pessoa];
            return $patient;
        }

        public function test_search_by_name_without_digits_does_not_use_digit_indexes(): void
        {
            $patients = [
                $this->makePatient(['id' => 1, 'primeiro_nome' => 'Matéus', 'ultimo_nome' => 'Silva']),
            ];
            Patient::setCollection($patients);

            $controller = new PatientController();
            $request = new \Illuminate\Http\Request(['query' => 'Mateus']);
            $result = $controller->search($request);

            $this->assertSame([
                ['id' => 1, 'nome' => 'Matéus Silva', 'email' => null, 'telefone' => '', 'cpf' => ''],
            ], $result);

            $used = Patient::$lastQuery->usedIndexes;
            $this->assertSame(true, in_array('pessoas_normalized_name_index', $used));
            $this->assertSame(false, in_array('pessoas_digits_phone_index', $used));
            $this->assertSame(false, in_array('pessoas_digits_whatsapp_index', $used));
            $this->assertSame(false, in_array('pessoas_digits_cpf_index', $used));
        }

        public function test_search_by_email_without_digits_does_not_use_digit_indexes(): void
        {
            $patients = [
                $this->makePatient(['id' => 1, 'primeiro_nome' => 'Jane', 'ultimo_nome' => 'Doe', 'email' => 'jane@example.com']),
            ];
            Patient::setCollection($patients);

            $controller = new PatientController();
            $request = new \Illuminate\Http\Request(['query' => 'jane@example.com']);
            $result = $controller->search($request);

            $this->assertSame([
                ['id' => 1, 'nome' => 'Jane Doe', 'email' => 'jane@example.com', 'telefone' => '', 'cpf' => ''],
            ], $result);

            $used = Patient::$lastQuery->usedIndexes;
            $this->assertSame(true, in_array('pessoas_normalized_name_index', $used));
            $this->assertSame(false, in_array('pessoas_digits_phone_index', $used));
            $this->assertSame(false, in_array('pessoas_digits_whatsapp_index', $used));
            $this->assertSame(false, in_array('pessoas_digits_cpf_index', $used));
        }

        public function test_search_by_phone_uses_phone_index(): void
        {
            $patients = [
                $this->makePatient(['id' => 1, 'primeiro_nome' => 'Ana', 'ultimo_nome' => 'Silva', 'phone' => '(11) 91234-5678']),
            ];
            Patient::setCollection($patients);

            $controller = new PatientController();
            $request = new \Illuminate\Http\Request(['query' => '11912345678']);
            $result = $controller->search($request);

            $this->assertSame([
                ['id' => 1, 'nome' => 'Ana Silva', 'email' => null, 'telefone' => '(11) 91234-5678', 'cpf' => ''],
            ], $result);

            $used = Patient::$lastQuery->usedIndexes;
            $this->assertSame(true, in_array('pessoas_digits_phone_index', $used));
        }

        public function test_search_by_cpf_uses_cpf_index(): void
        {
            $patients = [
                $this->makePatient(['id' => 1, 'primeiro_nome' => 'Carlos', 'ultimo_nome' => 'Ferreira', 'cpf' => '123.456.789-00']),
            ];
            Patient::setCollection($patients);

            $controller = new PatientController();
            $request = new \Illuminate\Http\Request(['query' => '12345678900']);
            $result = $controller->search($request);

            $this->assertSame([
                ['id' => 1, 'nome' => 'Carlos Ferreira', 'email' => null, 'telefone' => '', 'cpf' => '123.456.789-00'],
            ], $result);

            $used = Patient::$lastQuery->usedIndexes;
            $this->assertSame(true, in_array('pessoas_digits_cpf_index', $used));
        }
    }

    $test = new PatientSearchTest();
    $test->test_search_by_name_without_digits_does_not_use_digit_indexes();
    $test->test_search_by_email_without_digits_does_not_use_digit_indexes();
    $test->test_search_by_phone_uses_phone_index();
    $test->test_search_by_cpf_uses_cpf_index();
    echo "Test passed\n";
}
