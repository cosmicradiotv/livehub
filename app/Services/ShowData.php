<?php

namespace t2t2\LiveHub\Services;

use Carbon\Carbon;

/**
 * Class ShowData
 *
 * @package t2t2\LiveHub\Services\Incoming
 */
class ShowData
{

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
	 * @var Carbon Start time for the show (scheduled or real)
	 */
	public $start_time;
}
