<?php

namespace t2t2\LiveHub\Custom;

use Carbon\Carbon;

class ValidatorRules {
	/**
	 * Can the date be parsed
	 *
	 * @param string $attribute
	 * @param \DateTime|string $value
	 *
	 * @return bool
	 */
	public function parseableDate($attribute, $value) {
		if ($value instanceof \DateTime) {
			return true;
		}

		try {
			Carbon::parse($value); // Catches most stupidity
			return true;
		} catch (\Exception $e) {
			return false;
		}
	}

	/**
	 * Checks if value is a valid regex
	 *
	 * http://stackoverflow.com/a/26777713
	 *
	 * @param string $attribute
	 * @param string $value
	 * @return bool
	 */
	public function validRegex($attribute, $value) {
		set_error_handler(function () {
		}, E_WARNING);
		$isRegEx = preg_match($value, '') !== false;
		restore_error_handler();

		return $isRegEx;
	}

	/**
	 * Tests if value is a time (HH:MM(:SS))
	 *
	 * @param string $attribute
	 * @param string $value
	 * @return mixed
	 */
	public function time($attribute, $value) {
		return preg_match('/^([01][0-9]|2[0-3]):([0-5][0-9])(?::([0-5][0-9]))?$/', $value);
	}

}
