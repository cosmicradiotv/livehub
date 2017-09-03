<?php

namespace t2t2\LiveHub\Services\Incoming;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Promise\PromiseInterface;
use Illuminate\Support\Collection;
use Psr\Http\Message\ResponseInterface;
use t2t2\LiveHub\Models\Channel;
use t2t2\LiveHub\Services\ShowData;

class HlsService extends Service {

	/**
	 * Nice name for the user
	 *
	 * @return string
	 */
	public function name() {
		return 'HLS';
	}

	/**
	 * Description of the service to show to user
	 *
	 * @return string
	 */
	public function description() {
		return 'Checking for HLS live streams';
	}

	/**
	 * Configuration settings available for channels
	 *
	 * @return array
	 */
	public function channelConfig() {
		return [
			['name' => 'hls_url', 'type' => 'text', 'label' => 'HLS Stream URL', 'rules' => ['required', 'url']]
		];
	}

	/**
	 * Is service configured to be checkable
	 *
	 * @return bool
	 */
	public function isCheckable() {
		return true;
	}

	/**
	 * Check channel for live streams
	 *
	 * @param \t2t2\LiveHub\Models\Channel $channel
	 *
	 * @return \GuzzleHttp\Promise\PromiseInterface
	 */
	public function check(Channel $channel) {
		$url = $channel->options->hls_url;

		$client = new Client([
		]);

		$promise = $this->getHlsStreamData($client, $url);
		$promise = $this->reformatData($promise, $channel);

		return $promise;
	}

	/**
	 * Check stream if it's live
	 *
	 * @param \GuzzleHttp\Client $client
	 * @param string $url
	 *
	 * @return \GuzzleHttp\Promise\PromiseInterface
	 */
	protected function getHlsStreamData(Client $client, $url) {
		return $client->getAsync($url);
	}

	/**
	 * Reformat data into livehub format
	 *
	 * @param \GuzzleHttp\Promise\PromiseInterface $promise
	 * @param \t2t2\LiveHub\Models\Channel $channel
	 *
	 * @return \GuzzleHttp\Promise\PromiseInterface
	 */
	protected function reformatData(PromiseInterface $promise, Channel $channel) {
		return $promise->then(function (ResponseInterface $response) use ($channel) {
			$streams = new Collection();
			$body = $response->getBody();

			if ($body->read(7) == '#EXTM3U') {
				// Valid stream hence live

				$data = new ShowData();
				$data->service_info = 'stream';
				$data->title = $channel->name;
				$data->state = 'live';

				$streams->push($data);
			}

			return $streams;
		}, function (RequestException $error) {
			$response = $error->getResponse();

			$statusCode = $response->getStatusCode();
			if ($statusCode && $statusCode >= 400 && $statusCode <= 599) {
				// Stream offline

				return new Collection();
			}

			// I dunno lol
			throw $error;
		});
	}

}
