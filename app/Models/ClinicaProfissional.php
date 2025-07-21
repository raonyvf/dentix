<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClinicaProfissional extends Model
{
    protected $table = 'clinica_profissional';

    protected $fillable = [
        'clinica_id',
        'profissional_id',
        'status',
        'comissao',
    ];

    public function clinic()
    {
        return $this->belongsTo(Clinic::class, 'clinica_id');
    }

    public function profissional()
    {
        return $this->belongsTo(User::class, 'profissional_id');
    }
}
