<?php

use App\Sources\Services\EventReminderService;
use App\Sources\Services\EventSchedulerService;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();


Schedule::call(function(){
            (new EventSchedulerService())->runScheduler();
        })
        ->everyMinute()
        ->onSuccess(function(){
            Log::info('EventSchedulerService success');
        })
        ->onFailure(function(){
            Log::error('EventSchedulerService error');
        })->name('EventSchedulerService');
