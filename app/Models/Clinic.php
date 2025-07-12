<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Plano;
class Clinic extends Model
{

    protected $fillable = [
        'nome', 'cnpj', 'responsavel', 'plano_id'
    ];

    public function plano()
    {
        return $this->belongsTo(Plano::class);
    }
}
