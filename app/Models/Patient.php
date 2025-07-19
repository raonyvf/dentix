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
        'first_name',
        'middle_name',
        'last_name',
        'responsavel',
        'responsavel_first_name',
        'responsavel_middle_name',
        'responsavel_last_name',
        'cpf',
        'menor_idade',
        'responsavel_cpf',
        'email',
        'cep',
        'endereco_rua',
        'numero',
        'complemento',
        'bairro',
        'cidade',
        'estado',
        'data_nascimento',
        'idade',
        'telefone',
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
