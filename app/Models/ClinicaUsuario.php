<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;
use App\Models\Perfil;

class ClinicaUsuario extends Pivot
{
    protected $table = 'clinica_usuario';

    public function perfil()
    {
        return $this->belongsTo(Perfil::class);
    }
}
