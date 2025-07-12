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

    protected $casts = [
        'horarios_funcionamento' => 'array',
    ];

    public function clinic()
    {
        return $this->belongsTo(Clinic::class);
    }
}
