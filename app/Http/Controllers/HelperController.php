<?php namespace t2t2\LiveHub\Http\Controllers;

use Response;
use t2t2\LiveHub\Http\Requests;

class HelperController extends Controller {

	/**
	 * Display a page in case of misconfiguration
	 *
	 * @return Response
	 */
	public function misconfigured() {
		return view('helpers.misconfigured');
	}

	/**
	 * Display a page in case of nothing's live
	 *
	 * @return Response
	 */
	public function notlive() {
		return view('helpers.notlive');
	}

}
