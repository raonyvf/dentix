<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class Cnpj implements Rule
{
    public function passes($attribute, $value)
    {
        $cnpj = preg_replace('/\D/', '', $value);

        if (strlen($cnpj) != 14 || preg_match('/^(\d)\1+$/', $cnpj)) {
            return false;
        }

        $calc = function($cnpj, $length) {
            $sum = 0;
            $pos = $length - 7;
            for ($i = $length; $i >= 1; $i--) {
                $sum += $cnpj[$length - $i] * $pos--;
                if ($pos < 2) $pos = 9;
            }
            $result = $sum % 11;
            return ($result < 2) ? 0 : 11 - $result;
        };

        $digit1 = $calc($cnpj, 12);
        $digit2 = $calc($cnpj, 13);

        return $cnpj[12] == $digit1 && $cnpj[13] == $digit2;
    }

    public function message()
    {
        return 'CNPJ inválido.';
    }
}
