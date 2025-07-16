<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\BelongsToOrganization;
use App\Models\Organization;

class Cadeira extends Model
{
    use BelongsToOrganization;

    protected $fillable = [
        'clinic_id',
        'organization_id',
        'nome',
        'especialidade',
        'status',
    ];

    public function clinic()
    {
        return $this->belongsTo(Clinic::class);
    }

    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }
}
