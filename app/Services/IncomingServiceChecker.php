<?php

namespace t2t2\LiveHub\Services;

use Carbon\Carbon;
use DB;
use Exception;
use GuzzleHttp\Promise\RejectedPromise;
use Illuminate\Support\Collection;
use Log;
use t2t2\LiveHub\Models\Channel;
use t2t2\LiveHub\Models\Stream;
use t2t2\LiveHub\Services\Incoming\Service;

class IncomingServiceChecker {

	/**
	 * @var \t2t2\LiveHub\Services\ServicesGatherer
	 */
	private $gatherer;

	/**
	 * @var \Illuminate\Support\Collection|\t2t2\LiveHub\Services\Incoming\Service[]
	 */
	protected $services;

	/**
	 * @var \t2t2\LiveHub\Services\ShowRuleMatcher
	 */
	private $matcher;

	/**
	 * @param \t2t2\LiveHub\Services\ServicesGatherer $gatherer
	 * @param \t2t2\LiveHub\Services\ShowRuleMatcher $matcher
	 */
	public function __construct(ServicesGatherer $gatherer, ShowRuleMatcher $matcher) {
		$this->gatherer = $gatherer;
		$this->matcher = $matcher;
	}

	/**
	 * Checks services for new streams in channel
	 *
	 * @param \t2t2\LiveHub\Models\Channel $channel
	 *
	 * @return \GuzzleHttp\Promise\PromiseInterface
	 */
	public function check(Channel $channel) {
		if (!$this->services) {
			$this->readServices();
		}
		if (!$this->services[$channel->incoming_service_id]) {
			return null;
		}

		$promise = $this->services[$channel->incoming_service_id]->check($channel);

		$promise->then(function (Collection $streams) use ($channel) {
			$found_streams = $streams->pluck('service_info')->all();
			$database_streams = $channel->streams->pluck('service_info')->all();

			// Remove ended streams
			$this->removeEndedStreams(array_diff($database_streams, $found_streams), $channel);
			// Update other streams (including new ones)
			$this->updateStreams($streams, $channel);

			$channel->last_checked = Carbon::now();
			$channel->save(['timestamps' => false]);
		})->otherwise(function (Exception $e) use ($channel) {
			$this->logError($e, $channel);

			$channel->last_checked = Carbon::now();
			$channel->save(['timestamps' => false]);
			return new RejectedPromise($e);
		});

		return $promise;
	}

	/**
	 * Read incoming services into cache
	 *
	 * @return \Illuminate\Support\Collection|\t2t2\LiveHub\Services\Incoming\Service[]
	 */
	public function readServices() {
		/* @var \Illuminate\Support\Collection|\t2t2\LiveHub\Services\Incoming\Service[] $services */
		$this->services = $this->gatherer->allIncomingServices()->filter(function (Service $service) {
			return $service->getSettings() && $service->isCheckable();
		})->keyBy(function (Service $service) {
			return $service->getSettings()->id;
		});

		return $this->services;
	}

	/**
	 * Removes streams that have ended
	 *
	 * @param array $streamIDs
	 * @param \t2t2\LiveHub\Models\Channel $channel
	 * @return void
	 */
	protected function removeEndedStreams($streamIDs, Channel $channel) {
		foreach ($streamIDs as $endedID) {
			$stream = $channel->streams->first(function (Stream $stream, $key) use ($endedID) {
				return $stream->service_info == $endedID;
			});

			if ($stream) {
				// Delete from database
				$stream->delete();

				// Remove from relation
				$channel->setRelation('streams', $channel->streams->except($stream->id));
			}
		}
	}

	/**
	 * Updates and creates streams
	 *
	 * @param \Illuminate\Support\Collection|\t2t2\LiveHub\Services\ShowData[] $streams
	 * @param \t2t2\LiveHub\Models\Channel $channel
	 * @return void
	 */
	protected function updateStreams(Collection $streams, Channel $channel) {
		$streams->map(function (ShowData $data) use ($channel) {
			$stream = $this->getStream($data, $channel);
			if (!$stream) {
				return;
			}

			$stream->title = $data->title;
			$stream->state = $data->state;
			$stream->start_time = $data->start_time ?: ($stream->start_time ?: Carbon::now());

			if ($stream->isDirty()) {
				$stream->save();
				// TODO: Update event
			}
		});
	}

	/**
	 * Get stream object for the show data
	 *
	 * @param \t2t2\LiveHub\Services\ShowData $data
	 * @param \t2t2\LiveHub\Models\Channel $channel
	 *
	 * @return \t2t2\LiveHub\Models\Stream|null
	 */
	protected function getStream(ShowData $data, Channel $channel) {
		$stream = $channel->streams->first(function (Stream $stream, $key) use ($data) {
			return $stream->service_info == $data->service_info;
		});
		if ($stream) {
			return $stream;
		}

		$matching_show = $this->matchesShow($data, $channel);

		if ($matching_show) {
			$stream = new Stream();
			$stream->channel_id = $channel->id;
			$stream->service_info = $data->service_info;
			$stream->show_id = $matching_show->id;

			return $stream;
		}

		return null;
	}

	/**
	 * Find the show that best matches the given ShowData
	 *
	 * @param \t2t2\LiveHub\Services\ShowData $data
	 * @param \t2t2\LiveHub\Models\Channel $channel
	 *
	 * @return \t2t2\LiveHub\Models\Show
	 */
	protected function matchesShow(ShowData $data, Channel $channel) {
		$matched = 0;
		$matchedShow = null;
		// Find best matching show
		foreach ($channel->shows as $show) {
			$rules = json_decode($show->pivot->rules);

			$match = array_sum(array_map(function ($rule) use ($data) {
				return $this->matcher->match($rule, $data);
			}, $rules));

			if ($match > $matched) {
				$matchedShow = $show;
				$matched = $match;
			}
		}

		if ($matched > 0) {
			return $matchedShow;
		}

		return $channel->defaultShow;
	}

	/**
	 * Log errors that happen when checking the stream
	 *
	 * @param \Exception $e
	 * @param \t2t2\LiveHub\Models\Channel $channel
	 * @return void
	 */
	protected function logError(Exception $e, Channel $channel) {
		// Log to database
		DB::table('errors')->insert(
			[
				'text' => 'Error retrieving info from service:' . "\n\n" . $e->getMessage(),
				'channel_id' => $channel->id,
				'created_at' => Carbon::now()
			]
		);

		// Log error
		Log::error(
			'Error retrieving info from service',
			['message' => $e->getMessage(), 'channel' => $channel->id, 'code' => $e->getCode()]
		);
	}

}
