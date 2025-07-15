<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\BelongsToClinic;

class Horario extends Model
{
    use BelongsToClinic;

    protected $fillable = [
        'clinic_id',
        'dia_semana',
        'hora_inicio',
        'hora_fim',
    ];

    public function clinic()
    {
        return $this->belongsTo(Clinic::class);
    }
}
