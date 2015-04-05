<?php namespace t2t2\LiveHub\Http\Requests;

use Illuminate\Auth\Guard;
use Illuminate\Contracts\Validation\Factory;
use Illuminate\Validation\Validator;

class ShowChannelRequest extends Request {

	protected $allData;

	/**
	 * Determine if the user is authorized to make this request.
	 *
	 * @return bool
	 */
	public function authorize(Guard $auth) {
		return $auth->check();
	}

	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array
	 */
	public function rules() {
		$rules = [
			'rules' => 'array'
		];

		return $rules;
	}

	/**
	 * Generates a validator
	 *
	 * @param Factory $factory
	 *
	 * @return Validator
	 */
	public function validator(Factory $factory) {
		$input = $this->all();
		$input['rules'] = json_decode($input['rules'], true);

		/** @var Validator $validator */
		$validator = $factory->make($input, $this->rules(), $this->messages(), $this->attributes());

		foreach ($input['rules'] as $i => $value) {
			$validator->mergeRules("rules.{$i}.type", ['required', 'in:title,startBetween']);
			switch ($value['type']) {
				case 'title':
					$validator->mergeRules("rules.{$i}.rule", ['required', 'valid_regex']);
					break;
				case 'startBetween':
					$validator->mergeRules("rules.{$i}.days", ['required', 'array', 'min:1']);
					$validator->each("rules.{$i}.days", ['required', 'between:0,6']);
					$validator->mergeRules("rules.{$i}.start", ['required', 'time']);
					$validator->mergeRules("rules.{$i}.end", ['required', 'time']);
					break;
			}
		}

		return $validator;
	}


}
