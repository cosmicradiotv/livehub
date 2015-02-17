<?php
namespace t2t2\LiveHub\Services\Incoming;

use Carbon\Carbon;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Database\Eloquent\Collection;
use t2t2\LiveHub\Models\Channel;
use t2t2\LiveHub\Models\Stream;

class TwitchService extends Service {

	/**
	 * Nice name for the user
	 * @return string
	 */
	public function name() {
		return 'Twitch';
	}

	/**
	 * Description of the service to show to user
	 * @return string
	 */
	public function description() {
		return 'Checking for twitch livestreams';
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
		if ($channel && $channel->options && isset($channel->options->channel_username)) {
			return 'http://www.twitch.tv/' . $channel->options->channel_username . '/embed';
		}

		return parent::getVideoUrl($channel, $stream);
	}


	/**
	 * Get chat URL for this service
	 *
	 * @param null|Channel $channel
	 * @param null|Stream  $stream
	 *
	 * @return string
	 */
	public function getChatUrl($channel = null, $stream = null) {
		if ($channel && $channel->options && isset($channel->options->channel_username)) {
			return 'http://www.twitch.tv/' . $channel->options->channel_username . '/chat?popout=';
		}

		return parent::getChatUrl($channel, $stream);
	}

	/**
	 * Configuration setting available for this service
	 * @return array
	 */
	public function serviceConfig() {
		return [
			['name' => 'client_key', 'type' => 'text', 'label' => 'Twitch Application Client ID', 'rules' => ['required']],
		];
	}

	public function channelConfig() {
		return [
			['name' => 'channel_username', 'type' => 'text', 'label' => 'Channel Username', 'rules' => ['required']],
		];
	}

	/**
	 * Is the service configured to be checkable
	 * @return bool
	 */
	public function isCheckable() {
		return isset($this->getOptions()->client_key) && strlen($this->getOptions()->client_key) > 0;
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

		$username = $channel->options->channel_username;

		$client = new Client([
			'base_url' => 'https://api.twitch.tv/kraken/',
			'defaults' => [
				'headers' => [
					'Accept'    => 'application/vnd.twitchtv.v3+json',
					'Client-ID' => $this->getOptions()->client_key,
				],
				'query'   => [
				],
				'verify'  => storage_path('cacert.pem'),
			],
		]);

		try {
			$response = $client->get('streams/' . $username, [
				'query' => [
				],
			]);
			$results = $response->json();

			$stream = $results['stream'];
		} catch (RequestException $e) {
			$error = 'Unknown';
			if ($e->hasResponse()) {
				$response = $e->getResponse()->json();
				$error = $response['message'];
			}

			\Log::error('Error retrieving info from twitch',
				['message' => $error, 'channel' => $channel->id]);

			return [];
		}

		// Should only have one stream per channel
		$database_stream = $channel->streams->first();

		if (! $stream) {
			// Stream over
			if ($database_stream) {
				$database_stream->delete();
			}
		} else {
			if (! $database_stream) {
				$database_stream = new Stream();
				$database_stream->channel_id = $channel->id;
			}

			$database_stream->title = $stream['channel']['status'];
			$database_stream->state = 'live';
			$database_stream->start_time = Carbon::parse($stream['created_at']);

			$database_stream->save();
		}

		return $streams;
	}

}