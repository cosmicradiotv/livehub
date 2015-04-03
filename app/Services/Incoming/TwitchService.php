<?php
namespace t2t2\LiveHub\Services\Incoming;

use Carbon\Carbon;
use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Message\Response;
use Illuminate\Support\Collection;
use React\Promise\ExtendedPromiseInterface;
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
	 * @return ExtendedPromiseInterface
	 */
	public function check(Channel $channel) {
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

		$promise = $this->requestChannelInformation($client, $username);
		$promise = $this->transformStreamDataToLocal($promise);
		$promise = $this->reformatServiceErrors($promise);

		return $promise;
	}

	/**
	 * Request information for channel from twitch
	 *
	 * @param Client $client
	 * @param        $username
	 *
	 * @returns ExtendedPromiseInterface
	 */
	protected function requestChannelInformation(Client $client, $username) {
		return \React\Promise\resolve($client->get('streams/' . $username, [
			'future' => true
		]));
	}

	/**
	 * Convert data from twitch to locally usable format
	 *
	 * @param ExtendedPromiseInterface $promise
	 *
	 * @return ExtendedPromiseInterface
	 */
	protected function transformStreamDataToLocal(ExtendedPromiseInterface $promise) {
		return $promise->then(function(Response $response) {
			$results = $response->json();
			$stream = $results['stream'];

			$streams = new Collection();

			if($stream) {
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
	 * @param ExtendedPromiseInterface $promise
	 *
	 * @return ExtendedPromiseInterface
	 */
	protected function reformatServiceErrors(ExtendedPromiseInterface $promise) {
		return $promise->otherwise(function (RequestException $e) {
			// If request error happens anywhere, try to find the error message and use that
			if ($e->hasResponse()) {
				$response = $e->getResponse()->json();
				if (isset($response['message'])) {
					throw new Exception($response['message'], $e->getCode(), $e);
				}
			}
			throw $e;
		});
	}

}