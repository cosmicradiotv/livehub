<?php
namespace t2t2\LiveHub\Http\Controllers;


use Illuminate\Http\Response;

class LiveController extends Controller {


	/**
	 * @return Response
	 */
	public function home() {
		return view('live.home');
	}


}