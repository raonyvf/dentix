<?php

namespace App\Jobs;

use App\Models\User;
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
        protected User $user,
        protected string $password,
    ) {
    }

    public function handle(): void
    {
        $this->user->notify(new NewAdminPasswordNotification($this->password));
    }
}
