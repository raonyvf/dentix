<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\BelongsToClinic;

class Unidade extends Model
{
    use BelongsToClinic;

    protected $fillable = [
        'clinic_id', 'nome', 'endereco', 'cidade', 'estado', 'contato', 'horarios_funcionamento'
    ];

    public function clinic()
    {
        return $this->belongsTo(Clinic::class);
    }
}
