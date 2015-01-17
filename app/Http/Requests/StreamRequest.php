<?php namespace t2t2\LiveHub\Http\Requests;

use Illuminate\Auth\Guard;
use t2t2\LiveHub\Http\Requests\Request;

class StreamRequest extends Request {

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
		return [
			'title'      => ['required', 'max:255'],
			'channel_id' => ['required', 'exists:channels,id'],
			'state'      => ['required', 'in:next,live'],
			'start_time' => ['required', 'date'],
			'video_url'  => ['url'],
			'chat_url'   => ['url'],
		];
	}

	/**
	 * Get the sanitized input for the request.
	 *
	 * @return array
	 */
	public function sanitize() {
		return $this->all();
	}

}
