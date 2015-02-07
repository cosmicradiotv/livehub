<?php namespace t2t2\LiveHub\Console\Commands;

use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Collection as DatabaseCollection;
use Illuminate\Support\Collection;
use t2t2\LiveHub\Models\Channel;
use t2t2\LiveHub\Models\IncomingService;
use t2t2\LiveHub\Services\Incoming\Service;
use t2t2\LiveHub\Services\ServicesGatherer;

class CronServiceChecker extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'services:cron';

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
	 * Create a new command instance.
	 *
	 * @param ServicesGatherer $services
	 */
	public function __construct(ServicesGatherer $services) {
		parent::__construct();
		$this->services = $services;
	}

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function fire() {

		/** @var Collection|Service[] $services */
		$services = $this->services->allIncomingServices()->filter(function(Service $service) {
			return $service->isCheckable();
		})->keyBy(function(Service $service) {
			return $service->getSettings()->id;
		});

		/** @var DatabaseCollection|IncomingService[] $incomingServices */
		$incomingServices = new DatabaseCollection($services->map(function(Service $service) {
			return $service->getSettings();
		}));
		$incomingServices = $incomingServices->keyBy('id');
		$incomingServices->load('channels');

		/** @var DatabaseCollection|Channel[] $channels */
		$channels = new DatabaseCollection($incomingServices->lists('channels'));
		$channels = $channels->collapse();
		$channels->load('streams');

		// Filter to only have channels not recently checked
		$channels = $channels->filter(function(Channel $channel) {
			return $channel->last_checked->copy()->addSeconds(2 * 60)->isPast();
		});


		// Check all channels
		foreach($channels as $channel) {
			$streams = $services[$channel->incoming_service_id]->check($channel);

			$channel->last_checked = Carbon::now();
			$channel->save();
		}
	}

	/**
	 * Get the console command arguments.
	 *
	 * @return array
	 */
	protected function getArguments() {
		return [
		];
	}

	/**
	 * Get the console command options.
	 *
	 * @return array
	 */
	protected function getOptions() {
		return [
		];
	}

}
