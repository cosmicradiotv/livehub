<?php namespace t2t2\LiveHub\Http\Requests;

use Illuminate\Contracts\Auth\Guard;

class ChannelRequest extends Request {

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
			'name' => ['required', 'max:255'],
			'incoming_service_id' => ['required', 'exists:incoming_services,id'],
			'video_url' => ['url'],
			'chat_url' => ['url'],
			'default_show_id' => ['exists:shows,id'],
		];
	}

}
