<?php

namespace Carbon {
    class Carbon extends \DateTime {
        public const MONDAY = 1;

        public static function parse(string $date): self {
            return new self($date);
        }

        public function copy(): self {
            return clone $this;
        }

        public function startOfWeek(int $dayOfWeek): self {
            $this->modify('monday this week');
            return $this;
        }

        public function addWeek(): self {
            $this->modify('+1 week');
            return $this;
        }

        public function addWeeks(int $weeks): self {
            $this->modify("+$weeks week");
            return $this;
        }

        public function lte(self $other): bool {
            return $this <= $other;
        }

        public function isoWeekday(): int {
            return (int)$this->format('N');
        }

        public function toDateString(): string {
            return $this->format('Y-m-d');
        }
    }
}

namespace PHPUnit\Framework {
    class TestCase {
        protected function assertCount($expected, $array): void {
            if (count($array) !== $expected) {
                throw new \Exception('Failed asserting count');
            }
        }
    }
}

namespace App\Http\Controllers {
    class Controller {}
}

namespace App\Models {
    class HorarioCollection {
        public function __construct(public array $items) {}
        public function firstWhere($key, $value) {
            foreach ($this->items as $item) {
                $val = is_array($item) ? ($item[$key] ?? null) : ($item->$key ?? null);
                if ($val == $value) {
                    return (object)$item;
                }
            }
            return null;
        }
    }

    class Clinic {
        public static $instance;
        public $horarios;
        public static function with($relations) { return new self; }
        public function find($id) { return self::$instance; }
    }

    class EscalaTrabalho {
        public static $created = [];
        public static function create(array $data) { self::$created[] = $data; }
        public static function where(...$args) { return new self; }
        public function __call($name, $arguments) { return $this; }
        public function exists() { return false; }
    }

    class Cadeira {}
    class Profissional {}
}

namespace Illuminate\Validation {
    class Rule {
        public static function exists($table, $column) { return new self; }
        public function where($callback) { return $this; }
    }
}

namespace App\Enums {
    enum DiaSemana: int {
        case segunda = 1;
        case terca = 2;
        case quarta = 3;
        case quinta = 4;
        case sexta = 5;
        case sabado = 6;
        case domingo = 7;

        public static function fromName(string $name): ?self {
            return match($name) {
                'segunda' => self::segunda,
                'terca' => self::terca,
                'quarta' => self::quarta,
                'quinta' => self::quinta,
                'sexta' => self::sexta,
                'sabado' => self::sabado,
                'domingo' => self::domingo,
                default => null,
            };
        }
    }
}

namespace Illuminate\Http {
    class Request {
        public function __construct(public array $data) {}
        public function filled($key){ return !empty($this->data[$key]); }
        public function input($key, $default = null){ return $this->data[$key] ?? $default; }
        public function validate(array $rules, array $messages = []){ return $this->data; }
    }
}

namespace {
    require_once __DIR__ . '/../../app/Http/Controllers/Admin/EscalaTrabalhoController.php';
    use App\Http\Controllers\Admin\EscalaTrabalhoController;
    use PHPUnit\Framework\TestCase;
    use Illuminate\Http\Request;

    if (!function_exists('redirect')) {
        function redirect() {
            return new class {
                public function route($name, $params){ return $this; }
                public function with($key, $value){ return [$key => $value]; }
            };
        }
    }

    if (!function_exists('back')) {
        function back() {
            return new class {
                public function with($key, $value){ return [$key => $value]; }
            };
        }
    }

    class EscalaTrabalhoTest extends TestCase {
        private function bootClinic(): void {
            \App\Models\EscalaTrabalho::$created = [];
            \App\Models\Clinic::$instance = new class {
                public $horarios;
                public function __construct() {
                    $this->horarios = new \App\Models\HorarioCollection([
                        ['dia_semana' => 1, 'hora_inicio' => '08:00', 'hora_fim' => '18:00'],
                        ['dia_semana' => 2, 'hora_inicio' => '08:00', 'hora_fim' => '18:00'],
                        ['dia_semana' => 3, 'hora_inicio' => '08:00', 'hora_fim' => '18:00'],
                        ['dia_semana' => 4, 'hora_inicio' => '08:00', 'hora_fim' => '18:00'],
                        ['dia_semana' => 5, 'hora_inicio' => '08:00', 'hora_fim' => '18:00'],
                        ['dia_semana' => 6, 'hora_inicio' => '08:00', 'hora_fim' => '18:00'],
                        ['dia_semana' => 7, 'hora_inicio' => '08:00', 'hora_fim' => '18:00'],
                    ]);
                }
            };
        }

        public function test_store_daily_creates_entries() {
            $this->bootClinic();
            $controller = new EscalaTrabalhoController();
            $req = new Request([
                'clinic_id' => 1,
                'cadeira_id' => 1,
                'profissional_id' => 1,
                'datas' => ['2025-01-10', '2025-01-11'],
                'hora_inicio' => '09:00',
                'hora_fim' => '10:00',
            ]);
            $controller->store($req);
            $this->assertCount(2, \App\Models\EscalaTrabalho::$created);
        }

        public function test_store_recurring_creates_entries() {
            $this->bootClinic();
            $controller = new EscalaTrabalhoController();
            $req = new Request([
                'clinic_id' => 1,
                'cadeira_id' => 1,
                'profissional_id' => 1,
                'semana' => '2025-01-06',
                'dias' => ['segunda', 'quarta'],
                'hora_inicio' => '09:00',
                'hora_fim' => '10:00',
                'repeat_weeks' => 1,
            ]);
            $controller->store($req);
            $this->assertCount(2, \App\Models\EscalaTrabalho::$created);
        }
    }

    $test = new EscalaTrabalhoTest();
    $test->test_store_daily_creates_entries();
    $test->test_store_recurring_creates_entries();
    echo "Tests passed\n";
}

