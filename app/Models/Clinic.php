<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Horario;
use App\Models\Cadeira;
use App\Models\ClinicaProfissional;
use App\Models\HorarioProfissional;
use App\Traits\BelongsToOrganization;
use App\Models\Organization;
use App\Models\ClinicUser;

class Clinic extends Model
{
    use BelongsToOrganization;

    protected $fillable = [
        'nome',
        'cnpj',
        'responsavel_tecnico',
        'responsavel_first_name',
        'responsavel_middle_name',
        'responsavel_last_name',
        'cro',
        'logradouro',
        'numero',
        'complemento',
        'bairro',
        'cidade',
        'estado',
        'cep',
        'telefone',
        'email',
    ];

    public function horarios()
    {
        return $this->hasMany(Horario::class);
    }

    public function cadeiras()
    {
        return $this->hasMany(Cadeira::class);
    }

    public function clinicasProfissional()
    {
        return $this->hasMany(ClinicaProfissional::class, 'clinica_id');
    }

    public function horariosProfissional()
    {
        return $this->hasMany(HorarioProfissional::class, 'clinica_id');
    }

    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class)
            ->using(ClinicUser::class)
            ->withPivot('profile_id')
            ->withTimestamps();
    }
}
