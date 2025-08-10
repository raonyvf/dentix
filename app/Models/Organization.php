<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Organization extends Model
{
    protected $table = 'organizacoes';
    protected $fillable = [
        'nome_fantasia',
        'razao_social',
        'cnpj',
        'email',
        'telefone',
        'responsavel_primeiro_nome',
        'responsavel_nome_meio',
        'responsavel_ultimo_nome',
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
