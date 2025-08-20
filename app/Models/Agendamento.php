<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use App\Observers\AgendamentoObserver;

class Agendamento extends Model
{
    use HasFactory;

    protected $fillable = [
        'clinica_id',
        'profissional_id',
        'paciente_id',
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

    public function clinica()
    {
        return $this->belongsTo(Clinic::class);
    }

    public function profissional()
    {
        return $this->belongsTo(Profissional::class);
    }

    public function paciente()
    {
        return $this->belongsTo(Patient::class);
    }

    protected static function booted()
    {
        static::observe(AgendamentoObserver::class);

        static::saved(function (Agendamento $agendamento) {
            $date = $agendamento->data->format('Y-m-d');
            $cacheKey = "agendamentos_{$agendamento->clinica_id}_{$date}";
            Cache::forget($cacheKey);
        });
    }
}
