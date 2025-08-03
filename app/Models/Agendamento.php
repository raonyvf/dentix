<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

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

    protected static function booted()
    {
        static::saved(function (Agendamento $agendamento) {
            $date = $agendamento->data->format('Y-m-d');
            $cacheKey = "agendamentos_{$agendamento->clinic_id}_{$date}";
            Cache::forget($cacheKey);
        });
    }
}
