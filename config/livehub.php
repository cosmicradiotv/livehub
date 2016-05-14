<?php

return [

	/**
	 * Branding
	 *
	 * Title to use for the site
	 */

	'brand' => env('LIVEHUB_BRAND', 'LiveHub'),

	/**
	 * Checker Driver
	 *
	 * Checker to use for checking external services
	 *
	 * Options: "none", "cron"
	 */

	'checker' => env('LIVEHUB_CHECKER', 'none'),

];
