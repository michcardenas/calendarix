<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Cobrar trials vencidos diariamente a las 06:00
Schedule::command('bamboo:charge-expired-trials')->dailyAt('06:00');
