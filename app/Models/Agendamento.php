<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Agendamento extends Model
{
    use HasFactory;

    protected $fillable = [
        'clinic_id',
        'profissional_id',
        'patient_id',
        'tipo',
        'contato',
        'status',
        'data',
        'hora_inicio',
        'hora_fim',
        'observacao',
    ];

    protected $casts = [
        'data' => 'date',
    ];

    public function clinic()
    {
        return $this->belongsTo(Clinic::class);
    }

    public function profissional()
    {
        return $this->belongsTo(Profissional::class);
    }

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }
}
