<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cadeira extends Model
{

    protected $fillable = [
        'unidade_id', 'nome', 'especialidade', 'status'
    ];

    public function unidade()
    {
        return $this->belongsTo(Unidade::class);
    }
}
