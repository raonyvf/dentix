<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Perfil;

class Permissao extends Model
{
    protected $table = 'permissoes';
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
