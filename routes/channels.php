<?php

use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('horarios-liberados', function () {
    return true;
});
