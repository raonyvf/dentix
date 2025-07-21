<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\BelongsToOrganization;

class EmployeeContract extends Model
{
    use BelongsToOrganization;

    protected $fillable = [
        'organization_id',
        'clinic_user_id',
        'tipo_contrato',
        'data_inicio',
        'data_fim',
    ];

    protected $casts = [
        'data_inicio' => 'date',
        'data_fim' => 'date',
    ];

    public function clinicUser()
    {
        return $this->belongsTo(ClinicUser::class);
    }

    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }
}
