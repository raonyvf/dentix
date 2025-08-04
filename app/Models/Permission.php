<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Perfil;

class Permission extends Model
{
    protected $fillable = [
        'perfil_id',
        'modulo',
        'leitura',
        'escrita',
        'atualizacao',
        'exclusao',
    ];

    public function perfil()
    {
        return $this->belongsTo(Perfil::class);
    }
}
