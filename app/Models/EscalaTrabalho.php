<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\BelongsToOrganization;
use App\Traits\BelongsToClinic;

class EscalaTrabalho extends Model
{
    use BelongsToOrganization, BelongsToClinic;

    protected $table = 'escalas_trabalho';

    protected $fillable = [
        'organizacao_id',
        'clinica_id',
        'cadeira_id',
        'profissional_id',
        'semana',
        'dia_semana',
        'hora_inicio',
        'hora_fim',
    ];

    protected $casts = [
        'semana' => 'date',
    ];

    public function profissional()
    {
        return $this->belongsTo(Profissional::class);
    }

    public function cadeira()
    {
        return $this->belongsTo(Cadeira::class);
    }

    public function clinic()
    {
        return $this->belongsTo(Clinic::class, 'clinica_id');
    }
}
