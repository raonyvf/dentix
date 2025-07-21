<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HorarioProfissional extends Model
{
    protected $table = 'horarios_profissional';

    protected $fillable = [
        'clinica_id',
        'profissional_id',
        'dia_semana',
        'hora_inicio',
        'hora_fim',
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
