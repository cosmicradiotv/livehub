<?php
namespace t2t2\LiveHub\Services\Incoming;

class YoutubeService extends Service {

	/**
	 * Nice name for the user
	 *
	 * @return string
	 */
	public function name() {
		return 'Youtube Live';
	}

	/**
	 * Description of the service to show to user
	 *
	 * @return string
	 */
	public function description() {
		return 'Checking for youtube livestreams';
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


}