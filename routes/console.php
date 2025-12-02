<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

/*
|--------------------------------------------------------------------------
| Scheduled Tasks
|--------------------------------------------------------------------------
|
| Here you may define all of your scheduled tasks. These commands will
| run automatically via Laravel's task scheduler when you setup a cron job.
|
| Setup cron job (Linux/Mac): * * * * * cd /path-to-project && php artisan schedule:run >> /dev/null 2>&1
| Setup Task Scheduler (Windows): See documentation below
|
*/

// Update booking statuses daily at midnight (00:00)
Schedule::command('bookings:update-status')
    ->daily()
    ->timezone('Asia/Colombo') // Sri Lanka timezone
    ->onSuccess(function () {
        \Log::info('Scheduled task: bookings:update-status completed successfully');
    })
    ->onFailure(function () {
        \Log::error('Scheduled task: bookings:update-status failed');
    });

// Deactivate expired plans daily at 1:00 AM
Schedule::command('plans:deactivate-expired')
    ->dailyAt('01:00')
    ->timezone('Asia/Colombo') // Sri Lanka timezone
    ->onSuccess(function () {
        \Log::info('Scheduled task: plans:deactivate-expired completed successfully');
    })
    ->onFailure(function () {
        \Log::error('Scheduled task: plans:deactivate-expired failed');
    });
