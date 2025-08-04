<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Horario;
use App\Models\Cadeira;
use App\Traits\BelongsToOrganization;
use App\Models\Organization;
use App\Models\ClinicaUsuario;
use App\Models\Profissional;

class Clinic extends Model
{
    use BelongsToOrganization;

    protected $table = 'clinicas';

    protected $fillable = [
        'nome',
        'cnpj',
        'responsavel_first_name',
        'responsavel_middle_name',
        'responsavel_last_name',
        'cro',
        'cro_uf',
        'logradouro',
        'numero',
        'complemento',
        'bairro',
        'cidade',
        'estado',
        'cep',
        'telefone',
        'email',
        'timezone',
    ];

    public function horarios()
    {
        return $this->hasMany(Horario::class);
    }

    public function cadeiras()
    {
        return $this->hasMany(Cadeira::class, 'clinica_id');
    }


    public function organization()
    {
        return $this->belongsTo(Organization::class, 'organizacao_id');
    }

    public function usuarios()
    {
        return $this->belongsToMany(Usuario::class, 'clinica_usuario', 'clinica_id', 'usuario_id')
            ->using(ClinicaUsuario::class)
            ->withPivot('perfil_id')
            ->withTimestamps();
    }

    public function profissionais()
    {
        return $this->belongsToMany(Profissional::class, 'clinica_profissional')->withTimestamps();
    }
}
