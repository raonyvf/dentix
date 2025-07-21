<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\BelongsToOrganization;
use App\Traits\BelongsToClinic;

class WorkSchedule extends Model
{
    use BelongsToOrganization, BelongsToClinic;

    protected $fillable = [
        'organization_id',
        'clinic_id',
        'user_id',
        'dia_semana',
        'hora_inicio',
        'hora_fim',
    ];

    public function clinic()
    {
        return $this->belongsTo(Clinic::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }
}
