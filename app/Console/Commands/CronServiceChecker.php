<?php namespace t2t2\LiveHub\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Collection as DatabaseCollection;
use Illuminate\Support\Collection;
use Symfony\Component\Console\Input\InputOption;
use t2t2\LiveHub\Models\Channel;
use t2t2\LiveHub\Models\IncomingService;
use t2t2\LiveHub\Services\Incoming\Service;
use t2t2\LiveHub\Services\IncomingServiceChecker;
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
	 * @var ServicesGatherer
	 */
	private $services;

	/**
	 * @var IncomingServiceChecker
	 */
	private $checker;

	/**
	 * Create a new command instance.
	 *
	 * @param ServicesGatherer       $services
	 * @param IncomingServiceChecker $checker
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

		/** @var Collection|Service[] $services */
		$services = $this->services->allIncomingServices()->filter(function (Service $service) {
			return $service->isCheckable();
		})->keyBy(function (Service $service) {
			return $service->getSettings()->id;
		});

		/** @var DatabaseCollection|IncomingService[] $incomingServices */
		$incomingServices = new DatabaseCollection($services->map(function (Service $service) {
			return $service->getSettings();
		}));
		$incomingServices = $incomingServices->keyBy('id');
		$incomingServices->load('channels');

		/** @var DatabaseCollection|Channel[] $channels */
		$channels = $incomingServices->lists('channels');
		$channels = $channels->collapse();
		$channels->load('streams');

		// Filter to only have channels not recently checked
		$channels = $channels->filter(function (Channel $channel) {
			return $channel->last_checked ? $channel->last_checked->copy()->addSeconds(2 * 60)->isPast() : true;
		});

		// Check all channels
		foreach ($channels as $channel) {
			$promise = $this->checker->check($channel);
			$promise->then(function () use($channel) {
				$this->info('Channel checked: #'.$channel->id);
			});
		}
	}
}
