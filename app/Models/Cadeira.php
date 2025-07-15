<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\BelongsToClinic;

class Cadeira extends Model
{
    use BelongsToClinic;

    protected $fillable = [
        'clinic_id',
        'nome',
        'especialidade',
        'status',
    ];

    public function clinic()
    {
        return $this->belongsTo(Clinic::class);
    }
}
