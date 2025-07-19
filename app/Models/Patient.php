<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\BelongsToOrganization;
use App\Traits\BelongsToClinic;
use App\Models\Organization;
use Carbon\Carbon;

class Patient extends Model
{
    use BelongsToOrganization, BelongsToClinic;

    protected $fillable = [
        'clinic_id',
        'organization_id',
        'nome',
        'responsavel',
        'cpf',
        'menor_idade',
        'responsavel_cpf',
        'data_nascimento',
        'idade',
        'telefone',
        'email',
        'cep',
        'endereco_rua',
        'numero',
        'complemento',
        'bairro',
        'cidade',
        'estado',
        'ultima_consulta',
        'proxima_consulta',
    ];

    protected $dates = [
        'data_nascimento',
        'ultima_consulta',
        'proxima_consulta',
    ];

    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }

    public function getIdadeAttribute($value)
    {
        if ($this->data_nascimento) {
            return Carbon::parse($this->data_nascimento)->age;
        }

        return $value;
    }
}
