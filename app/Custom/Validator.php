<?php
namespace t2t2\LiveHub\Custom;

use Carbon\Carbon;
use DateTime;
use Exception;
use Illuminate\Validation\Validator as BaseValidator;

class Validator extends BaseValidator {

	/**
	 * Validate that an attribute is a valid date.
	 *
	 * Fixed to allow for relative dates
	 *
	 * @param  string  $attribute
	 * @param  mixed   $value
	 * @return bool
	 */
	protected function validateDate($attribute, $value) {
		if ($value instanceof DateTime) {
			return true;
		}

		try {
			Carbon::parse($value); // Catches most stupidity
			return true;
		} catch(Exception $e) {
			return false;
		}
	}

	/**
	 * Tests if the value is a regex
	 *
	 * @param $attribute
	 * @param $value
	 *
	 * @return bool
	 */
	protected function validateValidRegex($attribute, $value){
		set_error_handler(function() {}, E_WARNING);
		$isRegEx = preg_match($value, "") !== false;
		restore_error_handler();
		return $isRegEx;
	}

	/**
	 * Tests if value is a time (HH:MM(:SS))
	 *
	 * @param $attribute
	 * @param $value
	 *
	 * @return bool
	 */
	protected function validateTime($attribute, $value) {
		return $this->validateRegex($attribute, $value, ['/^([01][0-9]|2[0-3]):([0-5][0-9])(?::([0-5][0-9]))?$/']);
	}

}