<?php namespace t2t2\LiveHub\Http\Requests;

use Illuminate\Contracts\Auth\Guard;

class StreamRequest extends Request {

	/**
	 * Determine if the user is authorized to make this request.
	 *
	 * @param \Illuminate\Contracts\Auth\Guard $auth
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
			'title' => ['required', 'max:255'],
			'channel_id' => ['required', 'exists:channels,id'],
			'show_id' => ['required', 'exists:shows,id'],
			'state' => ['required', 'in:next,live'],
			'start_time' => ['required', 'date_parseable'],
			'video_url' => ['url'],
			'chat_url' => ['url'],
		];
	}

}
