<?php
namespace t2t2\LiveHub\Services\Incoming;

use Carbon\Carbon;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Database\Eloquent\Collection;
use t2t2\LiveHub\Models\Channel;
use t2t2\LiveHub\Models\Stream;

class YoutubeService extends Service {

	/**
	 * Nice name for the user
	 * @return string
	 */
	public function name() {
		return 'Youtube Live';
	}

	/**
	 * Description of the service to show to user
	 * @return string
	 */
	public function description() {
		return 'Checking for youtube livestreams';
	}

	/**
	 * Get video URL for this service
	 *
	 * @param null|Channel $channel
	 * @param null|Stream  $stream
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
	 * Configuration setting available for this service
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
	 * @return bool
	 */
	public function isCheckable() {
		return isset($this->getOptions()->api_key) && strlen($this->getOptions()->api_key) > 0;
	}

	/**
	 * Check channel for live streams
	 *
	 * @param Channel $channel
	 *
	 * @return array
	 */
	public function check(Channel $channel) {
		$streams = [];

		$channel_id = $channel->options->channel_id;

		$client = new Client([
			'base_url' => 'https://www.googleapis.com/youtube/v3/',
			'defaults' => [
				'headers' => [

				],
				'query'   => [
					'key' => $this->getOptions()->api_key,
				],
				'verify'  => storage_path('cacert.pem'),
			],
		]);

		foreach (['upcoming', 'live'] as $type) {
			try {
				$response = $client->get('search', [
					'query' => [
						'part'      => 'snippet',
						'channelId' => $channel_id,
						'type'      => 'video',
						'eventType' => $type,
					],
				]);
				$results = $response->json();

				foreach ($results['items'] as $item) {
					$streams[$item['id']['videoId']] = [
						'service_info' => $item['id']['videoId'],
						'state'        => $type == 'upcoming' ? 'next' : 'live',
					];
				}
			} catch (RequestException $e) {
				$error = 'Unknown';
				if ($e->hasResponse()) {
					$response = $e->getResponse()->json();
					$error = $response['error']['errors'][0]['message'];
				}

				\Log::error('Error retrieving info from youtube',
					['message' => $error, 'channel' => $channel->id, 'type' => $type]);

				return [];
			}
		}

		/** @var Collection|Stream[] $databaseStreams */
		$databaseStreams = $channel->streams->keyBy('service_info');
		$inDatabase = $databaseStreams->lists('service_info');
		$actualStreams = array_keys($streams);
		$endedStreams = array_diff($inDatabase, $actualStreams);

		// Remove ended streams
		foreach ($endedStreams as $ended) {
			$databaseStreams[$ended]->delete();
		}

		$toUpdate = array_diff($actualStreams, $inDatabase);
		// Check if any needs updating
		foreach (array_intersect($inDatabase, $actualStreams) as $stream_id) {
			$stream_info = $streams[$stream_id];
			$stream_object = $databaseStreams[$stream_id];

			if ($stream_object->state != $stream_info['state']) {
				$toUpdate[] = $stream_id;
			}
		}

		// Get all needed data for new streams
		try {
			$ids = implode(',', $toUpdate);

			$response = $client->get('videos', [
				'query' => [
					'part' => 'snippet,liveStreamingDetails',
					'id'   => $ids,
				]
			]);

			$results = $response->json();

			foreach ($results['items'] as $item) {
				$stream_id = $item['id'];

				if (isset($databaseStreams[$stream_id])) {
					$stream_object = $databaseStreams[$stream_id];
				} else {
					$stream_object = new Stream();
					$stream_object->channel_id = $channel->id;
					$stream_object->service_info = $item['id'];
				}
				$stream_object->title = $item['snippet']['title'];
				$stream_object->state = $item['snippet']['liveBroadcastContent'] == 'upcoming' ? 'next' : 'live';
				if (isset($item['liveStreamingDetails']['actualStartTime'])) {
					$stream_object->start_time = Carbon::parse($item['liveStreamingDetails']['actualStartTime']);
				} elseif (isset($item['liveStreamingDetails']['scheduledStartTime'])) {
					$stream_object->start_time = Carbon::parse($item['liveStreamingDetails']['scheduledStartTime']);
				} else {
					$stream_object->start_time = Carbon::now();
				}

				$stream_object->save();
			}

		} catch (RequestException $e) {
			$error = 'Unknown';
			if ($e->hasResponse()) {
				$response = $e->getResponse()->json();
				$error = $response['error']['errors'][0]['message'];
			}

			\Log::error('Error retrieving info from youtube',
				['message' => $error, 'channel' => $channel->id, 'ids' => $toUpdate]);
		}

		return $streams;
	}

}