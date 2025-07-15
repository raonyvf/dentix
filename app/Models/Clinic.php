<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Horario;
use App\Models\Cadeira;
class Clinic extends Model
{

    protected $fillable = [
        'nome',
        'cnpj',
        'responsavel',
        'endereco',
        'cidade',
        'estado',
        'contato',
    ];

    public function horarios()
    {
        return $this->hasMany(Horario::class);
    }

    public function cadeiras()
    {
        return $this->hasMany(Cadeira::class);
    }
}
