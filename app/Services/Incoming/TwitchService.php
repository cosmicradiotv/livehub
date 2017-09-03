<?php
namespace t2t2\LiveHub\Services\Incoming;

use Carbon\Carbon;
use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Promise\PromiseInterface;
use Illuminate\Support\Collection;
use Psr\Http\Message\ResponseInterface;
use t2t2\LiveHub\Models\Channel;
use t2t2\LiveHub\Services\ShowData;

class TwitchService extends Service {

	/**
	 * Nice name for the user
	 *
	 * @return string
	 */
	public function name() {
		return 'Twitch';
	}

	/**
	 * Description of the service to show to user
	 *
	 * @return string
	 */
	public function description() {
		return 'Checking for twitch livestreams';
	}

	public function getUrl($channel = null, $stream = null) {
		if ($stream->service_info) {
			return 'https://www.twitch.tv/' . $channel->options->channel_username;
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
		if ($channel && $channel->options && isset($channel->options->channel_username)) {
			return 'http://player.twitch.tv/?channel=' . $channel->options->channel_username;
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
		if ($channel && $channel->options && isset($channel->options->channel_username)) {
			return 'http://www.twitch.tv/' . $channel->options->channel_username . '/chat?popout=';
		}

		return parent::getChatUrl($channel, $stream);
	}

	/**
	 * Configuration setting available for this service
	 *
	 * @return array
	 */
	public function serviceConfig() {
		return [
			[
				'name' => 'client_key',
				'type' => 'text',
				'label' => 'Twitch Application Client ID',
				'rules' => ['required']
			],
		];
	}

	public function channelConfig() {
		return [
			[
				'name' => 'channel_username',
				'type' => 'text',
				'label' => 'Channel Username',
				'rules' => ['required']
			],
		];
	}

	/**
	 * Is the service configured to be checkable
	 *
	 * @return bool
	 */
	public function isCheckable() {
		return isset($this->getOptions()->client_key) && strlen($this->getOptions()->client_key) > 0;
	}

	/**
	 * Check channel for live streams
	 *
	 * @param \t2t2\LiveHub\Models\Channel $channel
	 *
	 * @return \GuzzleHttp\Promise\PromiseInterface
	 */
	public function check(Channel $channel) {
		$username = $channel->options->channel_username;

		$client = new Client([
			'base_uri' => 'https://api.twitch.tv/kraken/',
			'headers' => [
				'Accept' => 'application/vnd.twitchtv.v3+json',
				'Client-ID' => $this->getOptions()->client_key,
			],
			'query' => [
			],
		]);

		$promise = $this->requestChannelInformation($client, $username);
		$promise = $this->transformStreamDataToLocal($promise);
		$promise = $this->reformatServiceErrors($promise);

		return $promise;
	}

	/**
	 * Request information for channel from twitch
	 *
	 * @param \GuzzleHttp\Client $client
	 * @param string $username
	 *
	 * @return \GuzzleHttp\Promise\PromiseInterface
	 */
	protected function requestChannelInformation(Client $client, $username) {
		return $client->getAsync('streams/' . $username);
	}

	/**
	 * Convert data from twitch to locally usable format
	 *
	 * @param \GuzzleHttp\Promise\PromiseInterface $promise
	 *
	 * @return \GuzzleHttp\Promise\PromiseInterface
	 */
	protected function transformStreamDataToLocal(PromiseInterface $promise) {
		return $promise->then(function (ResponseInterface $response) {
			$results = json_decode($response->getBody(), true);
			$stream = $results['stream'];

			$streams = new Collection();

			if ($stream) {
				$data = new ShowData();
				$data->service_info = $stream['channel']['name'];
				$data->title = $stream['channel']['status'];
				$data->state = 'live';
				$data->start_time = Carbon::parse($stream['created_at']);

				$streams->push($data);
			}

			return $streams;
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
				if (isset($response['message'])) {
					throw new Exception($response['message'], $e->getCode(), $e);
				}
			}
			throw $e;
		});
	}

}
