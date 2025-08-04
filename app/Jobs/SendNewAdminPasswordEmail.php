<?php

namespace App\Jobs;

use App\Models\Usuario;
use App\Notifications\NewAdminPasswordNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendNewAdminPasswordEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        protected Usuario $usuario,
        protected string $password,
    ) {
    }

    public function handle(): void
    {
        $this->usuario->notify(new NewAdminPasswordNotification($this->password));
    }
}
