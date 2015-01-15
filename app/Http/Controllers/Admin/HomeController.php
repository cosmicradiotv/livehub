<?php
namespace t2t2\LiveHub\Http\Controllers\Admin;

use Illuminate\Http\Response;

class HomeController extends AdminController {


	/**
	 * Admin home page
	 *
	 * @return Response
	 */
	public function index() {
		return view('admin.index');
	}

}