<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\BelongsToOrganization;
use App\Models\Organization;

class Patient extends Model
{
    use BelongsToOrganization;

    protected $fillable = [
        'clinic_id',
        'organization_id',
        'nome',
        'responsavel',
        'idade',
        'telefone',
        'ultima_consulta',
        'proxima_consulta',
    ];

    protected $dates = [
        'ultima_consulta',
        'proxima_consulta',
    ];

    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }
}
