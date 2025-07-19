<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\BelongsToOrganization;

class Patient extends Model
{
    use BelongsToOrganization;

    protected $fillable = [
        'organization_id',
        'nome',
        'nome_meio',
        'ultimo_nome',
        'data_nascimento',
        'cpf',
        'menor_idade',
        'responsavel_nome',
        'responsavel_nome_meio',
        'responsavel_ultimo_nome',
        'responsavel_cpf',
        'telefone',
        'email',
        'cep',
        'logradouro',
        'numero',
        'complemento',
        'bairro',
        'cidade',
        'estado',
    ];

    protected $casts = [
        'data_nascimento' => 'date',
        'menor_idade' => 'boolean',
    ];

    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }
}
