<?php namespace t2t2\LiveHub\Http\Controllers\Admin;

use Illuminate\Http\Response;
use Illuminate\Http\Request;
use t2t2\LiveHub\Http\Requests;
use t2t2\LiveHub\Http\Requests\ShowRequest;
use t2t2\LiveHub\Models\Show;

class ShowChannelController extends AdminController {

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param Show $show
	 *
	 * @return Response
	 */
	public function edit(Show $show, $channel_id) {
		$title = 'Edit | Show';

//		return view('admin.show.edit', compact('show', 'title'));
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param Show $show
	 *
	 * @return Response
	 */
	public function update(Show $show, $channel_id, ShowRequest $request) {

//		return redirect()->back()
//		                 ->with('status', 'Show updated');
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param Show $show
	 *
	 * @return Response
	 */
	public function destroy(Show $show, $channel_id) {

//		return redirect()->route('admin.show.index')->with('status', 'Show deleted');
	}

}
