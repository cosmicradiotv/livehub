<?php

namespace t2t2\LiveHub\Services;


use Carbon\Carbon;
use Exception;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Collection as DatabaseCollection;
use t2t2\LiveHub\Models\Channel;
use t2t2\LiveHub\Models\Show;
use t2t2\LiveHub\Models\Stream;
use t2t2\LiveHub\Services\Incoming\Service;
use t2t2\LiveHub\Services\ShowData;

class IncomingServiceChecker {

	/**
	 * @var ServicesGatherer
	 */
	private $gatherer;

	/**
	 * @var Collection|Service[]
	 */
	protected $services;
	/**
	 * @var ShowRuleMatcher
	 */
	private $matcher;

	/**
	 * @param ServicesGatherer $gatherer
	 * @param ShowRuleMatcher  $matcher
	 */
	public function __construct(ServicesGatherer $gatherer, ShowRuleMatcher $matcher) {
		$this->gatherer = $gatherer;
		$this->matcher = $matcher;
	}

	/**
	 * Checks services for new streams in channel
	 *
	 * @param Channel $channel
	 *
	 * @return null|\React\Promise\ExtendedPromiseInterface
	 */
	public function check(Channel $channel) {
		if (! $this->services) {
			$this->readServices();
		}
		if (! $this->services[$channel->incoming_service_id]) {
			return null;
		}

		$promise = $this->services[$channel->incoming_service_id]->check($channel);

		$promise->then(function (Collection $streams) use ($channel) {
			/** @var DatabaseCollection|Stream[] $channel_streams */
			$channel_streams = $channel->streams->keyBy('service_info');

			$found_streams = $streams->lists('service_info');
			$database_streams = $channel_streams->lists('service_info');

			// Remove ended streams
			$this->removeEndedStreams(array_diff($database_streams, $found_streams), $channel_streams, $channel);
			// Update other streams (including new ones)
			$this->updateStreams($streams, $channel_streams, $channel);

			$channel->last_checked = Carbon::now();
			$channel->save(['timestamps' => false]);
		}, function (Exception $e) use ($channel) {
			\Log::error('Error retrieving info from service',
				['message' => $e->getMessage(), 'channel' => $channel->id, 'code' => $e->getCode()]);

			$channel->last_checked = Carbon::now();
			$channel->save(['timestamps' => false]);
		});

		return $promise;
	}

	/**
	 * Read incoming services into cache
	 */
	public function readServices() {
		/** @var Collection|Service[] $services */
		$this->services = $this->gatherer->allIncomingServices()->filter(function (Service $service) {
			return $service->isCheckable();
		})->keyBy(function (Service $service) {
			return $service->getSettings()->id;
		});

		return $this->services;
	}

	/**
	 * Removes streams that have ended
	 *
	 * @param array                       $streamIDs
	 * @param DatabaseCollection|Stream[] $channel_streams
	 * @param Channel                     $channel
	 */
	protected function removeEndedStreams($streamIDs, DatabaseCollection $channel_streams, Channel $channel) {
		foreach ($streamIDs as $endedID) {
			$stream = $channel_streams[$endedID];
			// Delete from database
			$stream->delete();

			// Remove from relation
			$channel->setRelation('streams', $channel->streams->except($stream->id));
			$channel_streams = $channel_streams->except($endedID);
		}
	}

	/**
	 * Updates and creates streams
	 *
	 * @param Collection|ShowData[]       $streams
	 * @param DatabaseCollection|Stream[] $channel_streams
	 * @param Channel                     $channel
	 */
	protected function updateStreams(Collection $streams, DatabaseCollection $channel_streams, Channel $channel) {
		$streams->map(function (ShowData $data) use ($channel_streams, $channel) {
			$stream = $this->getStream($data, $channel_streams, $channel);
			if (! $stream) {
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
	 * @param ShowData                    $data
	 * @param DatabaseCollection|Stream[] $channel_streams
	 * @param Channel                     $channel
	 *
	 * @return Stream|null
	 */
	protected function getStream(ShowData $data, DatabaseCollection $channel_streams, Channel $channel) {
		if (isset($channel_streams[$data->service_info])) {
			return $channel_streams[$data->service_info];
		} else {
			$matching_show = $this->matchesShow($data, $channel);

			if ($matching_show) {
				$stream = new Stream();
				$stream->channel_id = $channel->id;
				$stream->service_info = $data->service_info;
				$stream->show_id = $matching_show->id;

				return $stream;
			} else {
				return null;
			}
		}
	}

	/**
	 * Find the show that best matches the given ShowData
	 *
	 * @param ShowData $data
	 * @param Channel  $channel
	 *
	 * @return Show
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
		} else {
			return $channel->defaultShow;
		}
	}

}