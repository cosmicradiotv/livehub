<?php
namespace t2t2\LiveHub\Services\Incoming;

use Carbon\Carbon;
use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Promise\PromiseInterface;
use Illuminate\Support\Collection;
use t2t2\LiveHub\Models\Channel;
use t2t2\LiveHub\Services\ShowData;

class YoutubeService extends Service {

	/**
	 * Nice name for the user
	 *
	 * @return string
	 */
	public function name() {
		return 'YouTube';
	}

	/**
	 * Description of the service to show to user
	 *
	 * @return string
	 */
	public function description() {
		return 'Checking for youtube livestreams';
	}

	public function getUrl($channel = null, $stream = null) {
		if ($stream->service_info) {
			return 'https://www.youtube.com/watch?v=' . $stream->service_info;
		}

		return parent::getUrl($channel, $stream);
	}

	/**
	 * Get video URL for this service
	 *
	 * @param null|\t2t2\LiveHub\Models\Channel $channel
	 * @param null|\t2t2\LiveHub\Models\Stream $stream
	 *
	 * @return string
	 */
	public function getVideoUrl($channel = null, $stream = null) {
		if ($stream->service_info) {
			return 'http://www.youtube.com/embed/' . $stream->service_info . '?autohide=1&autoplay=1';
		}

		return parent::getVideoUrl($channel, $stream);
	}

	/**
	 * Get chat URL for this service
	 *
	 * @param null|\t2t2\LiveHub\Models\Channel $channel
	 * @param null|\t2t2\LiveHub\Models\Stream $stream
	 *
	 * @return string
	 */
	public function getChatUrl($channel = null, $stream = null) {
		if ($stream->service_info) {
			$domain_parts = explode('://', config('app.url'));
			if (count($domain_parts) > 1) {
				$domain = $domain_parts[1];
			} else {
				$doamin = $domain_parts[0];
			}
			return 'http://www.youtube.com/live_chat?v=' . $stream->service_info . '&embed_domain=' . $domain;
		}

		return parent::getVideoUrl($channel, $stream);
	}

	/**
	 * Configuration setting available for this service
	 *
	 * @return array
	 */
	public function serviceConfig() {
		return [
			['name' => 'api_key', 'type' => 'text', 'label' => 'Youtube API Key', 'rules' => ['required']],
		];
	}

	public function channelConfig() {
		return [
			['name' => 'channel_id', 'type' => 'text', 'label' => 'Channel ID', 'rules' => ['required']],
		];
	}

	/**
	 * Is the service configured to be checkable
	 *
	 * @return bool
	 */
	public function isCheckable() {
		return isset($this->getOptions()->api_key) && strlen($this->getOptions()->api_key) > 0;
	}

	/**
	 * Check channel for live streams
	 *
	 * @param \t2t2\LiveHub\Models\Channel $channel
	 *
	 * @return \GuzzleHttp\Promise\PromiseInterface
	 */
	public function check(Channel $channel) {
		$channel_id = $channel->options->channel_id;

		$client = new Client([
			'base_uri' => 'https://www.googleapis.com/youtube/v3/',
			'query' => [
				'key' => $this->getOptions()->api_key,
			],
		]);

		$promise = \GuzzleHttp\Promise\all(
			array_map(
				$this->requestLiveOfTypeCallback($client, $channel_id),
				['upcoming', 'live']
//				['live'] // Check live only until better rate limiting is figured out
			)
		);
		$promise = $this->findVideoIDsFromRequest($promise);
		$promise = $this->requestDataForVideoIDs($promise, $client);
		$promise = $this->tranformVideoDataToLocal($promise);
		$promise = $this->reformatServiceErrors($promise);

		return $promise;
	}

	/**
	 * Returns a callback that finds live videos from channel depending on livestatus
	 *
	 * @param \GuzzleHttp\Client $client
	 * @param string $channel_id
	 *
	 * @return callable
	 */
	protected function requestLiveOfTypeCallback(Client $client, $channel_id) {
		return function ($type) use ($client, $channel_id) {
			// Get live videos that are upcoming or live
			return $client->getAsync('search', [
				'query' => $client->getConfig('query') + [
						'part' => 'snippet',
						'channelId' => $channel_id,
						'type' => 'video',
						'eventType' => $type,
						'maxResults' => 10
					],
			]);
		};
	}

	/**
	 * Finds video IDs from youtube search request
	 *
	 * @param \GuzzleHttp\Promise\PromiseInterface $promise
	 *
	 * @return \GuzzleHttp\Promise\PromiseInterface
	 */
	protected function findVideoIDsFromRequest(PromiseInterface $promise) {
		return $promise->then(function ($responses) {
			// Find the video IDs
			$ids = [];
			/* @var \Psr\Http\Message\ResponseInterface[] $responses */
			foreach ($responses as $response) {
				$results = json_decode($response->getBody(), true);
				foreach ($results['items'] as $item) {
					$ids[] = $item['id']['videoId'];
				}
			}

			// Duplicates can happen between different requests (ty caching)
			$ids = array_unique($ids);

			return $ids;
		});
	}

	/**
	 * Gets data from youtube API about the list of video IDs
	 *
	 * @param \GuzzleHttp\Promise\PromiseInterface $promise
	 * @param \GuzzleHttp\Client $client
	 *
	 * @return \GuzzleHttp\Promise\PromiseInterface
	 */
	protected function requestDataForVideoIDs(PromiseInterface $promise, Client $client) {
		return $promise->then(function ($ids) use ($client) {
			// Get data for all of the found videos
			if (count($ids) == 0) {
				return new Collection();
			}

			return $client->getAsync('videos', [
				'query' => $client->getConfig('query') + [
						'part' => 'snippet,liveStreamingDetails',
						'id' => implode(',', $ids),
					],
			]);
		});
	}

	/**
	 * Converts data from videos list to data livehub can use
	 *
	 * @param \GuzzleHttp\Promise\PromiseInterface $promise
	 *
	 * @return \GuzzleHttp\Promise\PromiseInterface
	 */
	protected function tranformVideoDataToLocal(PromiseInterface $promise) {
		return $promise->then(function ($response) {
			// Skip if no videos
			if ($response instanceof Collection) {
				return $response;
			}

			// Format data from the videos to universal updater
			/* @var \Psr\Http\Message\ResponseInterface $response */
			$results = json_decode($response->getBody(), true);

			$videos = array_map(function ($item) {
				/* Youtube bug: Search results may return cached response where live videos are listed
				even way after they're over. This does not happen in /videos (here) */
				if (!$item['snippet']['liveBroadcastContent'] || $item['snippet']['liveBroadcastContent'] == 'none') {
					return null;
				}

				$info = new ShowData();
				$info->service_info = $item['id'];
				$info->title = $item['snippet']['title'];
				$info->state = $item['snippet']['liveBroadcastContent'] == 'upcoming' ? 'next' : 'live';
				if (isset($item['liveStreamingDetails']['actualStartTime'])) {
					$info->start_time = Carbon::parse($item['liveStreamingDetails']['actualStartTime']);
				} elseif (isset($item['liveStreamingDetails']['scheduledStartTime'])) {
					$info->start_time = Carbon::parse($item['liveStreamingDetails']['scheduledStartTime']);
				}

				return $info;
			}, $results['items']);

			$videos = array_filter($videos);

			return Collection::make($videos);
		});
	}

	/**
	 * Reformat any service errors that may have happened
	 *
	 * @param \GuzzleHttp\Promise\PromiseInterface $promise
	 *
	 * @return \GuzzleHttp\Promise\PromiseInterface
	 */
	protected function reformatServiceErrors(PromiseInterface $promise) {
		return $promise->otherwise(function (RequestException $e) {
			// If request error happens anywhere, try to find the error message and use that
			if ($e->hasResponse()) {
				$response = json_decode($e->getResponse()->getBody(), true);
				if (isset($response['error']['errors'][0]['message'])) {
					throw new Exception($response['error']['errors'][0]['message'], $e->getCode(), $e);
				}
			}
			throw $e;
		});
	}

}
