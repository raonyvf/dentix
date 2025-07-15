<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\BelongsToClinic;

class Patient extends Model
{
    use BelongsToClinic;

    protected $fillable = [
        'clinic_id',
        'nome',
        'responsavel',
        'idade',
        'telefone',
        'ultima_consulta',
        'proxima_consulta',
    ];

    protected $dates = [
        'ultima_consulta',
        'proxima_consulta',
    ];
}
