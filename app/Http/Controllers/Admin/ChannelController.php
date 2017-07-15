<?php namespace t2t2\LiveHub\Http\Controllers\Admin;

use t2t2\LiveHub\Http\Requests\ChannelRequest;
use t2t2\LiveHub\Models\Channel;
use t2t2\LiveHub\Models\IncomingService;
use t2t2\LiveHub\Models\Show;

class ChannelController extends AdminController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index() {
		$channels = Channel::all()->load('service');
		$title = 'Channels';

		return view('admin.channel.index', compact('channels', 'title'));
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function create() {
		$services = IncomingService::all();
		$shows = Show::all();
		$title = 'Create | Channel';

		/* @var \t2t2\LiveHub\Models\IncomingService $currentService */
		$service_id = old('incoming_service_id');
		if ($service_id) {
			$currentService = $services->find($service_id);
		} else {
			$currentService = $services->first();
		}
		$currentServiceSettings = $currentService->getService()->channelConfig();

		return view('admin.channel.create', compact('services', 'shows', 'title', 'currentServiceSettings'));
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param \t2t2\LiveHub\Http\Requests\ChannelRequest $request
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function store(ChannelRequest $request) {
		$this->validateServiceRules($request);

		$channel = new Channel($request->only([
			'incoming_service_id',
			'name',
			'video_url',
			'chat_url',
			'default_show_id'
		]));
		$channel->options = $request->get('options');

		$channel->save();

		return redirect()->route('admin.channel.index')
			->with('status', 'Channel created');
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param \t2t2\LiveHub\Models\Channel $channel
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function edit(Channel $channel) {
		$services = IncomingService::all();
		$shows = Show::all();
		$title = 'Edit | Channel';

		/* @var \t2t2\LiveHub\Models\IncomingService $currentService */
		$currentService = $services->find(old('incoming_service_id', $channel->incoming_service_id));
		$currentServiceSettings = $currentService->getService()->channelConfig();

		return view('admin.channel.edit', compact('channel', 'services', 'shows', 'title', 'currentServiceSettings'));
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param \t2t2\LiveHub\Models\Channel $channel
	 * @param \t2t2\LiveHub\Http\Requests\ChannelRequest $request
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function update(Channel $channel, ChannelRequest $request) {
		$this->validateServiceRules($request);

		$channel->fill($request->only(['incoming_service_id', 'name', 'video_url', 'chat_url', 'default_show_id']));
		$channel->options = $request->get('options');

		$channel->save();

		return redirect()->route('admin.channel.index')->with('status', 'Channel updated');
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param \t2t2\LiveHub\Models\Channel $channel
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function destroy(Channel $channel) {
		$channel->delete();

		return redirect()->route('admin.channel.index')->with('status', 'Channel deleted');
	}

	/**
	 * Get channel settings for service
	 *
	 * @param \t2t2\LiveHub\Models\IncomingService $service
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function channelServiceSettings(IncomingService $service) {
		return view('partials.service.settings', ['config' => $service->getService()->channelConfig()]);
	}

	/**
	 * Validate service rules
	 *
	 * @param \t2t2\LiveHub\Http\Requests\ChannelRequest $request
	 * @return void
	 */
	protected function validateServiceRules(ChannelRequest $request) {
		/* @var \t2t2\LiveHub\Models\IncomingService $service */
		$service = IncomingService::findOrFail($request->get('incoming_service_id'));
		$this->validate($request, $service->getService()->channelValidationRules());
	}

}
