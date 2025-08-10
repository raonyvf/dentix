<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\BelongsToOrganization;
use App\Models\Usuario;
use App\Models\Pessoa;
use App\Models\Clinic;

class Patient extends Model
{
    use BelongsToOrganization;

    protected $table = 'pacientes';

    protected $fillable = [
        'organizacao_id',
        'usuario_id',
        'pessoa_id',
        'menor_idade',
        'responsavel_primeiro_nome',
        'responsavel_nome_meio',
        'responsavel_ultimo_nome',
        'responsavel_cpf',
    ];

    protected $casts = [
        'menor_idade' => 'boolean',
    ];

    public function organization()
    {
        return $this->belongsTo(Organization::class, 'organizacao_id');
    }

    public function usuario()
    {
        return $this->belongsTo(Usuario::class);
    }

    public function pessoa()
    {
        return $this->belongsTo(Pessoa::class);
    }

    public function clinicas()
    {
        return $this->belongsToMany(Clinic::class, 'clinica_paciente', 'paciente_id', 'clinica_id')->withTimestamps();
    }
}
