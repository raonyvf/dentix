<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\BelongsToOrganization;
use App\Traits\BelongsToClinic;
use App\Models\Organization;

class Cadeira extends Model
{
    use BelongsToOrganization, BelongsToClinic;

    protected $fillable = [
        'clinica_id',
        'organizacao_id',
        'nome',
        'status',
    ];

    public function clinic()
    {
        return $this->belongsTo(Clinic::class, 'clinica_id');
    }

    public function organization()
    {
        return $this->belongsTo(Organization::class, 'organizacao_id');
    }
}
