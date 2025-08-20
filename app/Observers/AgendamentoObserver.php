<?php

namespace App\Observers;

use App\Events\HorarioLiberado;
use App\Models\Agendamento;
use Illuminate\Support\Facades\Cache;

class AgendamentoObserver
{
    /**
     * Status que liberam o horário quando atualizados.
     */
    protected array $statusLiberados = ['cancelado', 'faltou'];

    public function deleted(Agendamento $agendamento): void
    {
        $this->liberar($agendamento);
        $this->clearCache($agendamento);
    }

    public function updated(Agendamento $agendamento): void
    {
        if ($agendamento->wasChanged('status') && in_array($agendamento->status, $this->statusLiberados, true)) {
            $this->liberar($agendamento);
        }
    }

    protected function liberar(Agendamento $agendamento): void
    {
        HorarioLiberado::dispatch(
            $agendamento->data->format('Y-m-d'),
            $agendamento->hora_inicio,
            $agendamento->profissional_id
        );
    }

    protected function clearCache(Agendamento $agendamento): void
    {
        $date = $agendamento->data->format('Y-m-d');
        $cacheKey = "agendamentos_{$agendamento->clinica_id}_{$date}";
        Cache::forget($cacheKey);
    }
}
