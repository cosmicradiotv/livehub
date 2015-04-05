<?php namespace t2t2\LiveHub\Http\Controllers\Admin;

use DB;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use t2t2\LiveHub\Http\Requests;
use t2t2\LiveHub\Http\Requests\ShowChannelRequest;
use t2t2\LiveHub\Http\Requests\ShowRequest;
use t2t2\LiveHub\Models\Channel;
use t2t2\LiveHub\Models\Show;

class ShowChannelController extends AdminController {

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param Show $show
	 *
	 * @return Response
	 */
	public function edit(Show $show, Channel $channel) {
		$rules = DB::table('channel_show')
		           ->where('channel_id', $channel->id)
		           ->where('show_id', $show->id)
		           ->pluck('rules');
		if ($rules) {
			$rules = json_decode($rules);
		} else {
			$rules = [];
		}

		$title = 'Edit | Channel | ' . $show->name . ' | Show';

		return view('admin.show.channel.edit', compact('show', 'channel', 'rules', 'title'));
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param Show               $show
	 * @param Channel            $channel
	 * @param ShowChannelRequest $request
	 *
	 * @return Response
	 */
	public function update(Show $show, Channel $channel, ShowChannelRequest $request) {

		$isExisting = DB::table('channel_show')
		                ->where('show_id', $show->id)
		                ->where('channel_id', $channel->id)
		                ->exists();

		if ($isExisting) {
			$show->channels()->updateExistingPivot($channel->id, $request->only('rules'));
		} else {
			$show->channels()->attach($channel->id, $request->only('rules'));
		}

		return redirect()->route('admin.show.edit', ['show' => $show])
		                 ->with('status', "Show's channel's settings updated");
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param Show $show
	 *
	 * @return Response
	 */
	public function destroy(Show $show, Channel $channel) {
		$isExisting = DB::table('channel_show')
		                ->where('show_id', $show->id)
		                ->where('channel_id', $channel->id)
		                ->exists();

		if ($isExisting) {
			$show->channels()->detach($channel->id);
		}

		return redirect()->route('admin.show.edit', ['show' => $show])
		                 ->with('status', "Show's Channel removed");
	}

	/**
	 * Redirect based on input variables
	 *
	 * @param Request $request
	 *
	 * @return \Illuminate\Http\RedirectResponse
	 */
	public function redirect(Request $request) {
		$show_id = $request->get('show_id');
		$channel_id = $request->get('channel_id');

		if ($show_id && $channel_id) {
			return redirect()->route('admin.show.channel.edit', ['show' => $show_id, 'channel' => $channel_id]);
		} else {
			throw new NotFoundHttpException;
		}
	}

}
