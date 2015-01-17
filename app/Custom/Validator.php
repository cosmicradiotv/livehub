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


}