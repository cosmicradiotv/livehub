<?php namespace t2t2\LiveHub\Http\Controllers\Admin;

use Illuminate\Http\Response;
use t2t2\LiveHub\Http\Requests;
use t2t2\LiveHub\Http\Requests\ChannelRequest;
use t2t2\LiveHub\Models\Channel;
use t2t2\LiveHub\Models\IncomingService;

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
		$title = 'Create | Channel';

		return view('admin.channel.create', compact('services', 'title'));
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store(ChannelRequest $request) {
		$channel = new Channel($request->only(['incoming_service_id', 'name', 'video_url', 'chat_url']));

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
		$title = 'Edit | Channel';

		return view('admin.channel.edit', compact('channel', 'services', 'title'));
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param Channel $channel
	 *
	 * @return Response
	 */
	public function update(Channel $channel, ChannelRequest $request) {
		$channel->fill($request->only(['incoming_service_id', 'name', 'video_url', 'chat_url']));

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

}
