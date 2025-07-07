<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\BelongsToClinic;

class Clinic extends Model
{
    use BelongsToClinic;

    protected $fillable = [
        'nome', 'cnpj', 'responsavel', 'plano', 'idioma_preferido'
    ];
}
