<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Organization extends Model
{
    protected $fillable = [
        'nome_fantasia',
        'razao_social',
        'cnpj',
        'email',
        'telefone',
        'responsavel_first_name',
        'responsavel_middle_name',
        'responsavel_last_name',
        'cep',
        'logradouro',
        'numero',
        'complemento',
        'bairro',
        'cidade',
        'estado',
        'logo_url',
        'status',
        'timezone',
    ];


    public function clinics()
    {
        return $this->hasMany(Clinic::class);
    }
}
