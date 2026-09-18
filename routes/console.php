<?php

use Illuminate\Support\Facades\Artisan;

Artisan::command('app:info', function () {
    $this->info('AulaAdaptativa: prototipo de aprendizaje adaptativo.');
})->purpose('Muestra información del proyecto');
