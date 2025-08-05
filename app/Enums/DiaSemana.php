<?php
namespace App\Enums;

enum DiaSemana: int
{
    case Segunda = 1;
    case Terca = 2;
    case Quarta = 3;
    case Quinta = 4;
    case Sexta = 5;
    case Sabado = 6;
    case Domingo = 7;

    public static function fromName(string $name): ?self
    {
        return match(strtolower($name)) {
            'segunda' => self::Segunda,
            'terca' => self::Terca,
            'quarta' => self::Quarta,
            'quinta' => self::Quinta,
            'sexta' => self::Sexta,
            'sabado' => self::Sabado,
            'domingo' => self::Domingo,
            default => null,
        };
    }

    public function toName(): string
    {
        return strtolower($this->name);
    }
}
