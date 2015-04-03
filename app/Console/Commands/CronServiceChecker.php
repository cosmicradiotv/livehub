<?php namespace t2t2\LiveHub\Console\Commands;

use Carbon\Carbon;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Collection as DatabaseCollection;
use Illuminate\Support\Collection;
use Symfony\Component\Console\Input\InputOption;
use t2t2\LiveHub\Models\Channel;
use t2t2\LiveHub\Models\IncomingService;
use t2t2\LiveHub\Models\Stream;
use t2t2\LiveHub\Services\Incoming\Service;
use t2t2\LiveHub\Services\Incoming\ShowData;
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

		if (config('livehub.checker') != 'cron' && ! $this->option('force')) {
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
		$channels = new DatabaseCollection($incomingServices->lists('channels'));
		$channels = $channels->collapse();
		$channels->load('streams');

		// Filter to only have channels not recently checked
		$channels = $channels->filter(function (Channel $channel) {
			return $channel->last_checked->copy()->addSeconds(2 * 60)->isPast();
		});

		// Check all channels
		foreach ($channels as $channel) {
			$streams = $services[$channel->incoming_service_id]->check($channel);

			$streams->done(function (Collection $streams) use ($channel) {
				/** @var Collection|Stream[] $channel_streams */
				$channel_streams = $channel->streams->keyBy('service_info');

				$found_streams = $streams->lists('service_info');
				$database_streams = $channel_streams->lists('service_info');

				// Remove ended streams
				$ended = array_diff($database_streams, $found_streams);
				foreach ($ended as $endedID) {
					$channel_streams[$endedID]->delete();
				}

				// Update other streams
				$streams->map(function (ShowData $data) use ($channel_streams, $channel) {
					if (isset($channel_streams[$data->service_info])) {
						$stream = $channel_streams[$data->service_info];
					} else {
						$stream = new Stream();
						$stream->channel_id = $channel->id;
						$stream->service_info = $data->service_info;

						// TODO: Advanced show matching rules
						if ($channel->default_show_id) {
							$stream->show_id = $channel->default_show_id;
						} else {
							return; // Matches none of the shows we're interested in.
						}
					}

					$stream->title = $data->title;
					$stream->state = $data->state;
					$stream->start_time = $data->start_time ?: ($stream->start_time ?: Carbon::now());

					if ($stream->isDirty()) {
						$stream->save();
						// TODO: Update event
					}
				});

				$channel->last_checked = Carbon::now();
				$channel->save(['timestamps' => false]);
			}, function (Exception $e) use ($channel) {
				\Log::error('Error retrieving info from service',
					['message' => $e->getMessage(), 'channel' => $channel->id, 'code' => $e->getCode()]);

				$channel->last_checked = Carbon::now();
				$channel->save(['timestamps' => false]);
			});

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
			['force', 'F', InputOption::VALUE_NONE, 'Run even in otherwise configured environments'],
		];
	}

}
