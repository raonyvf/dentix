<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Horario;
use App\Models\Cadeira;
use App\Traits\BelongsToOrganization;
use App\Models\Organization;

class Clinic extends Model
{
    use BelongsToOrganization;

    protected $fillable = [
        'nome',
        'cnpj',
        'responsavel',
        'endereco',
        'cidade',
        'estado',
        'contato',
    ];

    public function horarios()
    {
        return $this->hasMany(Horario::class);
    }

    public function cadeiras()
    {
        return $this->hasMany(Cadeira::class);
    }

    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }
}
