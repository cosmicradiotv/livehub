<?php namespace t2t2\LiveHub\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use t2t2\LiveHub\Console\Commands\CreateUser;
use t2t2\LiveHub\Console\Commands\CronServiceChecker;

class Kernel extends ConsoleKernel {

	/**
	 * The Artisan commands provided by your application.
	 *
	 * @var array
	 */
	protected $commands = [
		CreateUser::class,
		CronServiceChecker::class,
	];

	/**
	 * Define the application's command schedule.
	 *
	 * @param  \Illuminate\Console\Scheduling\Schedule $schedule
	 *
	 * @return void
	 */
	protected function schedule(Schedule $schedule) {
		// Every five minutes until rate limiting / batching happens
		$schedule->command('services:cron')->everyFiveMinutes()->environments('production')->when(function () {
			return config('livehub.checker') == 'cron';
		})->withoutOverlapping();
	}

}
