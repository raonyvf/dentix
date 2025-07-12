<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\BelongsToClinic;
use App\Models\Horario;

class Unidade extends Model
{
    use BelongsToClinic;

    protected $fillable = [
        'clinic_id', 'nome', 'endereco', 'cidade', 'estado', 'contato'
    ];

    public function clinic()
    {
        return $this->belongsTo(Clinic::class);
    }

    public function horarios()
    {
        return $this->hasMany(Horario::class);
    }
}
