<?php
require __DIR__.'/../vendor/autoload.php';
$app = require __DIR__.'/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Http\Request;
use App\Http\Controllers\Admin\AgendaController;

$date = $argv[1] ?? date('Y-m-d');
$clinicId = $argv[2] ?? null;

$request = Request::create('/admin/agendamentos/horarios', 'GET', ['date' => $date]);

if ($clinicId !== null) {
    app()->instance('clinic_id', (int) $clinicId);
}

$response = app(AgendaController::class)->horarios($request);

echo $response->getContent() . PHP_EOL;
