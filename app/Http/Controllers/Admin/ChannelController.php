<?php namespace t2t2\LiveHub\Http\Controllers\Admin;

use Illuminate\Http\Response;
use t2t2\LiveHub\Http\Requests;
use t2t2\LiveHub\Http\Requests\ChannelRequest;
use t2t2\LiveHub\Models\Channel;
use t2t2\LiveHub\Models\IncomingService;
use t2t2\LiveHub\Models\Show;
use t2t2\LiveHub\Services\ServicesGatherer;

class ChannelController extends AdminController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index() {
		$channels = Channel::all()->load('service');
		$title = 'Channels';

		return view('admin.channel.index', compact('channels', 'title'));
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create() {
		$services = IncomingService::all();
		$shows = Show::all();
		$title = 'Create | Channel';

		/** @var IncomingService $currentService */
		if ($service_id = old('incoming_service_id')) {
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
	 * @param ChannelRequest $request
	 *
	 * @return Response
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
	 * @param Channel $channel
	 *
	 * @return Response
	 */
	public function edit(Channel $channel) {
		$services = IncomingService::all();
		$shows = Show::all();
		$title = 'Edit | Channel';

		/** @var IncomingService $currentService */
		$currentService = $services->find(old('incoming_service_id', $channel->incoming_service_id));
		$currentServiceSettings = $currentService->getService()->channelConfig();

		return view('admin.channel.edit', compact('channel', 'services', 'shows', 'title', 'currentServiceSettings'));
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param Channel        $channel
	 * @param ChannelRequest $request
	 *
	 * @return Response
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
	 * @param Channel $channel
	 *
	 * @return Response
	 */
	public function destroy(Channel $channel) {
		$channel->delete();

		return redirect()->route('admin.channel.index')->with('status', 'Channel deleted');
	}

	/**
	 * Get channel settings for service
	 *
	 * @param IncomingService $service
	 *
	 * @return Response
	 */
	public function channelServiceSettings(IncomingService $service) {
		return view('partials.service.settings', ['config' => $service->getService()->channelConfig()]);
	}

	/**
	 * Validate service rules
	 *
	 * @param ChannelRequest $request
	 */
	protected function validateServiceRules(ChannelRequest $request) {
		/** @var IncomingService $service */
		$service = IncomingService::findOrFail($request->get('incoming_service_id'));
		$this->validate($request, $service->getService()->channelValidationRules());
	}

}
