<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    protected $fillable = [
        'profile_id',
        'modulo',
        'leitura',
        'escrita',
        'atualizacao',
        'exclusao',
    ];

    public function profile()
    {
        return $this->belongsTo(Profile::class);
    }
}
