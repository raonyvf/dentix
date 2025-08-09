<?php

namespace App\Models {
    class Patient
    {
        public static array $collection = [];
        private array $results = [];

        public static function setCollection(array $patients): void
        {
            self::$collection = $patients;
        }

        public static function with($relation): self
        {
            $instance = new self;
            $instance->results = self::$collection;
            return $instance;
        }

        public function where($field, $value): self
        {
            $this->results = array_filter($this->results, fn($p) => $p->$field == $value);
            return $this;
        }

        public function orWhereHas($relation, $callback): self
        {
            // Ignored for this test
            return $this;
        }

        public function limit($limit): self
        {
            return $this;
        }

        public function get()
        {
            return collect(array_values($this->results));
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
                (object) ['id' => 1, 'pessoa' => (object) ['primeiro_nome' => 'Alice', 'ultimo_nome' => 'Smith']],
                (object) ['id' => 2, 'pessoa' => (object) ['primeiro_nome' => 'Bob', 'ultimo_nome' => 'Jones']],
            ];
            \App\Models\Patient::setCollection($patients);

            $controller = new PatientController();
            $request = new \Illuminate\Http\Request(['q' => '2']);
            $result = $controller->search($request);

            $this->assertSame([
                ['id' => 2, 'name' => 'Bob Jones', 'phone' => '', 'cpf' => ''],
            ], $result);
        }
    }

    $test = new PatientSearchTest();
    $test->test_search_returns_patient_by_id();
    echo "Test passed\n";
}
