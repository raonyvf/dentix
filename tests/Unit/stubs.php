<?php
namespace Carbon {
    class Carbon extends \DateTime
    {
        public const MONDAY = 1;
        public const SUNDAY = 7;

        public static function parse(string $date): self
        {
            return new self($date);
        }

        public function copy(): self
        {
            return clone $this;
        }

        public function startOfWeek(int $dayOfWeek): self
        {
            // move to Monday of current week
            $this->modify('monday this week');
            return $this;
        }

        public function endOfWeek(int $dayOfWeek): self
        {
            // move to Sunday of current week
            $this->modify('sunday this week');
            return $this;
        }

        public function isoWeekday(): int
        {
            return (int) $this->format('N');
        }

        public function toDateString(): string
        {
            return $this->format('Y-m-d');
        }
    }
}

namespace {
    class Collection
    {
        private array $items;

        public function __construct(array $items = [])
        {
            $this->items = $items;
        }

        public function pluck(string $key): self
        {
            $values = [];
            foreach ($this->items as $item) {
                if (is_array($item)) {
                    $values[] = $item[$key] ?? null;
                } else {
                    $values[] = $item->$key ?? null;
                }
            }
            return new self($values);
        }

        public function unique(?string $key = null): self
        {
            $unique = [];
            $seen = [];
            foreach ($this->items as $item) {
                if ($key === null) {
                    $value = $item;
                } else {
                    $value = is_array($item) ? ($item[$key] ?? null) : ($item->$key ?? null);
                }
                if (!in_array($value, $seen, true)) {
                    $seen[] = $value;
                    $unique[] = $item;
                }
            }
            return new self($unique);
        }

        public function map(callable $callback): self
        {
            $mapped = [];
            foreach ($this->items as $item) {
                $mapped[] = $callback($item);
            }
            return new self($mapped);
        }

        public function values(): self
        {
            return new self(array_values($this->items));
        }

        public function toArray(): array
        {
            return $this->items;
        }
    }

    if (!function_exists('collect')) {
        function collect(array $items = []): Collection
        {
            return new Collection($items);
        }
    }
}

namespace PHPUnit\Framework {
    class TestCase
    {
        protected function assertSame($expected, $actual): void
        {
            if ($expected !== $actual) {
                throw new \Exception('Failed asserting that '.var_export($actual, true).' is identical to '.var_export($expected, true));
            }
        }
    }
}

namespace App\Http\Controllers {
    class Controller {}
}

namespace App\Models {
    class EscalaTrabalho
    {
        public static $collection;

        public static function setCollection($collection): void
        {
            self::$collection = $collection;
        }

        public static function __callStatic($name, $arguments)
        {
            $instance = new self;
            return $instance->$name(...$arguments);
        }

        public function __call($name, $arguments)
        {
            if ($name === 'get') {
                return self::$collection;
            }
            return $this;
        }
    }

    class Profissional
    {
        public static $collection;

        public static function setCollection($collection): void
        {
            self::$collection = $collection;
        }

        public static function with($relations)
        {
            return new self;
        }

        public function whereIn($field, $values)
        {
            return $this;
        }

        public function get()
        {
            return self::$collection;
        }
    }
}
