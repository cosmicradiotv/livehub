<?php

namespace t2t2\LiveHub\Services;

/**
 * Class ShowData
 */
class ShowData {

	/**
	 * @var string Identifier that service can use to check for duplicates
	 */
	public $service_info;

	/**
	 * @var string Title of the stream
	 */
	public $title;

	/**
	 * @var string State of the stream (next or live)
	 */
	public $state;

	/**
	 * @var \Carbon\Carbon Start time for the show (scheduled or real)
	 */
	public $start_time;

}
