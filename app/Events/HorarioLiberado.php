<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class HorarioLiberado implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public string $data;
    public string $hora;
    public int $profissionalId;

    public function __construct(string $data, string $hora, int $profissionalId)
    {
        $this->data = $data;
        $this->hora = $hora;
        $this->profissionalId = $profissionalId;
    }

    public function broadcastOn(): Channel
    {
        return new Channel('horarios-liberados');
    }

    public function broadcastWith(): array
    {
        return [
            'data' => $this->data,
            'hora' => $this->hora,
            'profissional_id' => $this->profissionalId,
        ];
    }
}
