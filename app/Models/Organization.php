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
        'endereco_faturamento',
        'logo_url',
        'status',
    ];

    protected $casts = [
        'endereco_faturamento' => 'array',
    ];

    public function clinics()
    {
        return $this->hasMany(Clinic::class);
    }
}
