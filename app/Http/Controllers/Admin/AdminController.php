<?php
namespace t2t2\LiveHub\Http\Controllers\Admin;

use t2t2\LiveHub\Http\Controllers\Controller;

class AdminController extends Controller
{

	/**
	 * Make sure user is authenticated
	 */
	public function __construct()
	{
		$this->middleware('auth');
	}
}
