<?php
namespace t2t2\LiveHub\Services\Incoming;

class DumbService extends Service {

	/**
	 * Nice name for the user
	 *
	 * @return string
	 */
	public function name() {
		return "Dumb service";
	}

	/**
	 * Description of the service to show to user
	 *
	 * @return string
	 */
	public function description() {
		return "This service is a dummy for attaching channels that don't have a livechecking service available";
	}


}