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
        'responsavel_nome',
        'responsavel_nome_meio',
        'responsavel_ultimo_nome',
        'cep',
        'rua',
        'numero',
        'complemento',
        'bairro',
        'cidade',
        'estado',
        'logo_url',
        'status',
    ];


    public function clinics()
    {
        return $this->hasMany(Clinic::class);
    }
}
