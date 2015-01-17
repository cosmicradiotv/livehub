<?php
namespace t2t2\LiveHub\Services\Incoming;

use Illuminate\Contracts\Routing\UrlRoutable;
use t2t2\LiveHub\Models\Channel;
use t2t2\LiveHub\Models\IncomingService;
use t2t2\LiveHub\Models\Stream;

abstract class Service implements UrlRoutable {

	protected $options;

	protected $settings;

	/**
	 * Description of the service to show to user
	 *
	 * @return string
	 */
	abstract public function description();

	/**
	 * Get chat URL for this service
	 *
	 * @param null|Channel $channel
	 * @param null|Stream $stream
	 *
	 * @return string
	 */
	public function getChatUrl($channel = null, $stream = null) {
		return route('helper.misconfigured');
	}

	/**
	 * @return mixed
	 */
	public function getOptions() {
		return $this->options;
	}

	/**
	 * Get the value of the model's route key.
	 *
	 * @return mixed
	 */
	public function getRouteKey() {
		return $this->id();
	}

	/**
	 * Get the route key for the model.
	 *
	 * @return string
	 */
	public function getRouteKeyName() {
		return 'id';
	}

	/**
	 * @return null
	 */
	public function getSettings() {
		return $this->settings;
	}

	/**
	 * @param IncomingService|null $settings
	 */
	public function setSettings($settings) {
		$this->settings = $settings;

		if($settings) {
			$this->options = $settings->options;
		} else {
			$this->options = null;
		}
	}

	/**
	 * Get video URL for this service
	 *
	 * @param null|Channel $channel
	 * @param null|Stream $stream
	 *
	 * @return string
	 */
	public function getVideoUrl($channel = null, $stream = null) {
		return route('helper.misconfigured');
	}

	/**
	 * Get the class ID for this
	 *
	 * @return string
	 */
	public function id() {
		return class_basename($this);
	}

	/**
	 * Nice name for the user
	 *
	 * @return string
	 */
	abstract public function name();
}