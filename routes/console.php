<?php

use Illuminate\Support\Facades\Schedule;

/*
|--------------------------------------------------------------------------
| Console Schedules & Cron Jobs
|--------------------------------------------------------------------------
|
| Automated background schedules for contract expiration monitoring, overdue payment
| tracking, and automated client echéance reminder notifications across all tenants.
|
*/

Schedule::command('contracts:check-expiry')->dailyAt('08:00');
Schedule::command('payments:check-overdue')->dailyAt('09:00');
Schedule::command('app:send-echeance-reminders 30')->dailyAt('08:30');
