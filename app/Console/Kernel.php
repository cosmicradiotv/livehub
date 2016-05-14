<?php namespace t2t2\LiveHub\Console;

use Exception;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use InvalidArgumentException;

class Kernel extends ConsoleKernel
{

	/**
	 * The Artisan commands provided by your application.
	 *
	 * @var array
	 */
	protected $commands = [
		Commands\CreateUser::class,
		Commands\CronServiceChecker::class,
	];

	/**
	 * Define the application's command schedule.
	 *
	 * @param  \Illuminate\Console\Scheduling\Schedule $schedule
	 *
	 * @return void
	 */
	protected function schedule(Schedule $schedule)
	{
		$schedule->command('services:cron')->cron('* * * * * *')->environments('production')->when(function () {
			return config('livehub.checker') == 'cron';
		})->withoutOverlapping();
	}
}
