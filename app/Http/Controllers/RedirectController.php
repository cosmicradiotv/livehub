<?php
namespace t2t2\LiveHub\Http\Controllers;

use Illuminate\Routing\Router;

class RedirectController extends Controller {

	/**
	 * @param \Illuminate\Routing\Router $route
	 *
	 * @return \Illuminate\Http\RedirectResponse
	 */
	public function redirect(Router $route) {
		$target = $route->current()->getAction()['to'];

		return redirect()->route($target, $route->current()->parameters());
	}

}
