<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProfissionalComissao extends Model
{
    protected $table = 'profissional_comissoes';
    protected $fillable = [
        'profissional_id',
        'clinica_id',
        'comissao',
        'protese',
    ];

    public function profissional()
    {
        return $this->belongsTo(Profissional::class);
    }

    public function clinica()
    {
        return $this->belongsTo(Clinic::class, 'clinica_id');
    }
}
