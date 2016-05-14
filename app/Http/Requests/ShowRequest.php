<?php namespace t2t2\LiveHub\Http\Requests;

use Illuminate\Contracts\Auth\Guard;
use t2t2\LiveHub\Models\Show;

class ShowRequest extends Request
{

	/**
	 * Determine if the user is authorized to make this request.
	 *
	 * @return bool
	 */
	public function authorize(Guard $auth)
    {
		return $auth->check();
	}

	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array
	 */
	public function rules()
    {
		/** @var Show $show */
		$show = $this->route('show');

		$rules = [
			'name' => ['required', 'max:255'],
			'slug' => ['required', 'max:255'],
		];

		if ($show) {
			$rules['slug'][] = "unique:shows,slug,{$show->id}";
		} else {
			$rules['slug'][] = "unique:shows,slug";
		}

		return $rules;
	}
}
