<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;
use App\Models\Perfil;

class ClinicUser extends Pivot
{
    protected $table = 'clinic_user';

    public function perfil()
    {
        return $this->belongsTo(Perfil::class);
    }
}
