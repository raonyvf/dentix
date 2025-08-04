<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\BelongsToOrganization;
use App\Traits\BelongsToClinic;

class ProfissionalHorario extends Model
{
    use BelongsToOrganization, BelongsToClinic;

    protected $fillable = [
        'organizacao_id',
        'clinica_id',
        'profissional_id',
        'dia_semana',
        'hora_inicio',
        'hora_fim',
    ];

    public function profissional()
    {
        return $this->belongsTo(Profissional::class);
    }

    public function clinic()
    {
        return $this->belongsTo(Clinic::class, 'clinica_id');
    }
}
