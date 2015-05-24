<?php
namespace t2t2\LiveHub\Http\Controllers\Admin;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Response;
use t2t2\LiveHub\Http\Requests\StreamRequest;
use t2t2\LiveHub\Models\Channel;
use t2t2\LiveHub\Models\ErrorLine;
use t2t2\LiveHub\Models\IncomingService;
use t2t2\LiveHub\Models\Show;
use t2t2\LiveHub\Models\Stream;

class HomeController extends AdminController {


	/**
	 * Admin home page
	 *
	 * @return Response
	 */
	public function index() {
		$channels = Channel::all();
		$streams = Stream::all();

		/** @var IncomingService $dummyService */
		$dummyService = IncomingService::whereClass('DumbService')->first();
		if ($dummyService) {
			$dummyChannels = $dummyService->channels;
		} else {
			$dummyChannels = new Collection();
		}
		$shows = Show::all();
		$errorlog = ErrorLine::query()->with('channel')->orderBy('created_at', 'DESC')->take(10)->get();

		$title = 'Admin';

		return view('admin.index', compact('streams', 'channels', 'shows', 'dummyChannels', 'title', 'errorlog'));
	}

	/**
	 * Quickly add a stream
	 *
	 * @param StreamRequest $request
	 *
	 * @return Response
	 */
	public function addStream(StreamRequest $request) {
		$stream = new Stream($request->only([
			'channel_id',
			'show_id',
			'service_info',
			'title',
			'state',
			'start_time',
			'video_url',
			'chat_url'
		]));

		$stream->save();

		return redirect()->back()->with('status', 'Stream Created');
	}

	/**
	 * Quickly mark a stream live
	 *
	 * @param Stream $stream
	 *
	 * @return \Illuminate\Http\RedirectResponse
	 * @throws \Exception
	 */
	public function streamLive(Stream $stream) {
		$stream->state = 'live';
		$stream->save();

		return redirect()->back()->with('status', 'Stream updated');
	}

	/**
	 * Quickly remove a stream
	 *
	 * @param Stream $stream
	 *
	 * @return \Illuminate\Http\RedirectResponse
	 * @throws \Exception
	 */
	public function streamDestroy(Stream $stream) {
		$stream->delete();

		return redirect()->back()->with('status', 'Stream removed');
	}

}