<?php

namespace t2t2\LiveHub\Services;

use Carbon\Carbon;

class ShowRuleMatcher
{

	/**
	 * Tests if show data matches for the rule
	 *
	 * @param          $rule
	 * @param ShowData $data
	 *
	 * @return int
	 */
	public function match($rule, ShowData $data)
	{
		$method = 'matches' . studly_case($rule->type);
		if (method_exists($this, $method)) {
			return $this->$method($rule, $data);
		}

		return 0;
	}

	/**
	 * Test if title matches the regex rule
	 *
	 * @param          $settings
	 * @param ShowData $data
	 *
	 * @return int
	 */
	protected function matchesTitle($settings, ShowData $data)
	{
		if (preg_match($settings->rule, $data->title)) {
			return 1;
		} else {
			return 0;
		}
	}

	/**
	 * Checks if start time is in range
	 *
	 * @param          $settings
	 * @param ShowData $data
	 *
	 * @return int
	 */
	protected function matchesStartBetween($settings, ShowData $data)
	{
		$time = $data->start_time ?: Carbon::now();
		$timeStart = $this->explodeTime($settings->start);
		$timeEnd = $this->explodeTime($settings->end);

		/**
		 * Check if time is in range
		 */
		/** @var Carbon[] $range */
		$range = [
			Carbon::create($time->year, $time->month, $time->day, $timeStart[0], $timeStart[1], $timeStart[2]),
			Carbon::create($time->year, $time->month, $time->day, $timeEnd[0], $timeEnd[1], $timeEnd[2])
		];
		$overnight = $range[0] > $range[1];
		// Overnight helpers
		$daymodifier = 0;
		if ($overnight) {
			// If start is earlier than the range start we'll want to have start be day earlier.
			if ($time < $range[0]) {
				$range[0]->subDay();
				$daymodifier--;
			} else {
				$range[1]->addDay();
			}
		}

		// Check if time is in range
		if (!$time->between($range[0], $range[1])) {
			return 0;
		}

		// Check if day is OK
		return in_array(
			($time->dayOfWeek + $daymodifier) >= 0 ? $time->dayOfWeek + $daymodifier : 6,
			$settings->days
		) ? 1 : 0;
	}

	/**
	 * Explode time into array
	 *
	 * @param $string
	 *
	 * @return array
	 */
	protected function explodeTime($string)
	{
		$parts = explode(':', $string);

		return [intval($parts[0]), intval($parts[1]), isset($parts[2]) ? intval($parts[2]) : 0];
	}
}
