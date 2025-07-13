<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\BelongsToClinic;

class Cadeira extends Model
{
    use BelongsToClinic;

    protected $fillable = [
        'unidade_id', 'nome', 'especialidade', 'status'
    ];

    public function unidade()
    {
        return $this->belongsTo(Unidade::class);
    }
}
