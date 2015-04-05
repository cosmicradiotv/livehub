<?php namespace t2t2\LiveHub\Http\Controllers\Admin;

use Illuminate\Http\Response;
use Illuminate\Http\Request;
use t2t2\LiveHub\Http\Requests;
use t2t2\LiveHub\Http\Requests\ShowRequest;
use t2t2\LiveHub\Models\Channel;
use t2t2\LiveHub\Models\Show;

class ShowController extends AdminController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index() {
		$shows = Show::all();
		$title = 'Shows';

		return view('admin.show.index', compact('shows', 'title'));
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create() {
		$title = 'Create | Show';

		return view('admin.show.create', compact('title'));
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store(ShowRequest $request) {
		$show = new Show($request->only('name', 'slug'));

		$show->save();

		return redirect()->route('admin.show.edit', ['show' => $show])
		                 ->with('status', 'Show created');
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param Show $show
	 *
	 * @return Response
	 */
	public function edit(Show $show) {
		$show->with('channels');

		$title = 'Edit | Show';

		$channels = Channel::whereNotIn('id', $show->channels->lists('id'))->lists('name', 'id');

		return view('admin.show.edit', compact('show', 'channels', 'title'));
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param Show $show
	 *
	 * @return Response
	 */
	public function update(Show $show, ShowRequest $request) {
		$show->fill($request->only('name', 'slug'));

		$show->save();

		return redirect()->back()
		                 ->with('status', 'Show updated');
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param Show $show
	 *
	 * @return Response
	 */
	public function destroy(Show $show) {
		$show->delete();

		return redirect()->route('admin.show.index')->with('status', 'Show deleted');
	}

}
