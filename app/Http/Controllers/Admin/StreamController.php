<?php namespace t2t2\LiveHub\Http\Controllers\Admin;

use Carbon\Carbon;
use Illuminate\Http\Response;
use t2t2\LiveHub\Http\Requests;
use t2t2\LiveHub\Http\Requests\StreamRequest;
use t2t2\LiveHub\Models\Channel;
use t2t2\LiveHub\Models\Show;
use t2t2\LiveHub\Models\Stream;

class StreamController extends AdminController {

	/**
	 * Fields that can be filled on the model
	 *
	 * @var array
	 */
	protected $fillable = [
		'channel_id',
		'show_id',
		'service_info',
		'title',
		'state',
		'start_time',
		'video_url',
		'chat_url'
	];

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index() {
		$streams = Stream::all()->load('channel', 'show');
		$title = 'Streams';

		return view('admin.stream.index', compact('streams', 'title'));
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create() {
		$channels = Channel::all();
		$shows = Show::all();
		$title = 'Create | Streams';

		return view('admin.stream.create', compact('channels', 'shows', 'title'));
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param StreamRequest $request
	 *
	 * @return Response
	 */
	public function store(StreamRequest $request) {
		$stream = new Stream($request->only($this->fillable));

		$stream->save();

		return redirect()->route('admin.stream.index')
		                 ->with('status', 'Stream created');
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  Stream $stream
	 *
	 * @return Response
	 */
	public function edit(Stream $stream) {
		$channels = Channel::all();
		$shows = Show::all();
		$title = 'Edit | Stream';

		return view('admin.stream.edit', compact('stream', 'channels', 'shows', 'title'));
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  Stream       $stream
	 * @param StreamRequest $request
	 *
	 * @return Response
	 */
	public function update(Stream $stream, StreamRequest $request) {
		$stream->fill($request->only($this->fillable));

		$stream->save();

		return redirect()->route('admin.stream.index')
		                 ->with('status', 'Stream updated');
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  Stream $stream
	 *
	 * @return Response
	 */
	public function destroy(Stream $stream) {
		$stream->delete();

		return redirect()->route('admin.stream.index')
		                 ->with('status', 'Stream deleted');
	}

}
