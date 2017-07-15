<?php namespace t2t2\LiveHub\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Collection as DatabaseCollection;
use t2t2\LiveHub\Models\Channel;
use t2t2\LiveHub\Services\IncomingServiceChecker;
use t2t2\LiveHub\Services\Incoming\Service;
use t2t2\LiveHub\Services\ServicesGatherer;

class CronServiceChecker extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $signature = 'services:cron
	                        {--force : Run even in otherwise configured environments}';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Cron based services checker';

	/**
	 * @var \t2t2\LiveHub\Services\ServicesGatherer
	 */
	private $services;

	/**
	 * @var \t2t2\LiveHub\Services\IncomingServiceChecker
	 */
	private $checker;

	/**
	 * Create a new command instance.
	 *
	 * @param \t2t2\LiveHub\Services\ServicesGatherer $services
	 * @param \t2t2\LiveHub\Services\IncomingServiceChecker $checker
	 */
	public function __construct(ServicesGatherer $services, IncomingServiceChecker $checker) {
		parent::__construct();

		$this->services = $services;
		$this->checker = $checker;
	}

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function handle() {
		if (config('livehub.checker') != 'cron' && !$this->option('force')) {
			$this->info('Not in use');

			return;
		}

		/* @var \Illuminate\Database\Eloquent\Collection|\t2t2\LiveHub\Services\Incoming\Service[] $services */
		$services = $this->services->allIncomingServices()->filter(function (Service $service) {
			return $service->getSettings() && $service->isCheckable();
		})->keyBy(function (Service $service) {
			return $service->getSettings()->id;
		});

		/* @var \Illuminate\Database\Eloquent\Collection|\t2t2\LiveHub\Models\IncomingService[] $incomingServices */
		$incomingServices = new DatabaseCollection($services->map(function (Service $service) {
			return $service->getSettings();
		}));
		$incomingServices = $incomingServices->keyBy('id');
		$incomingServices->load('channels');

		/* @var \Illuminate\Database\Eloquent\Collection|\t2t2\LiveHub\Models\Channel[] $channels */
		$channels = new DatabaseCollection($incomingServices->pluck('channels')->collapse());
		$channels->load('streams');

		// Filter to only have channels not recently checked
		$channels = $channels->filter(function (Channel $channel) {
			return $channel->last_checked ? $channel->last_checked->copy()->addSeconds(2 * 60)->isPast() : true;
		});

		// Check all channels
		$promises = [];
		foreach ($channels as $channel) {
			$promise = $this->checker->check($channel);
			$promise->then(function () use ($channel) {
				$this->info('Channel checked: #' . $channel->id);
			}, function ($e) use ($channel) {
				$this->error('Channel error: #' . $channel->id);
			});
			$promises[] = $promise;
		}
		// Wait for all promises
		$collected = \GuzzleHttp\Promise\settle($promises);
		$collected->wait(false);
	}

}
