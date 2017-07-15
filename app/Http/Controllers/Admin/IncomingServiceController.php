<?php namespace t2t2\LiveHub\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use t2t2\LiveHub\Models\IncomingService;
use t2t2\LiveHub\Services\ServicesGatherer;

class IncomingServiceController extends AdminController {

	/**
	 * @var \t2t2\LiveHub\Services\ServicesGatherer
	 */
	private $gatherer;

	/**
	 * @param \t2t2\LiveHub\Services\ServicesGatherer $gatherer
	 */
	public function __construct(ServicesGatherer $gatherer) {
		parent::__construct();

		$this->gatherer = $gatherer;
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index() {
		/* @var \t2t2\LiveHub\Services\Incoming\Service[] $services */
		$services = $this->gatherer->allIncomingServices();

		$title = 'Incoming Services';

		return view('admin.service.incoming.index', compact('services', 'title'));
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  string $class
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function edit($class) {
		$service = $this->gatherer->incomingService($class);

		if (!$service) {
			throw new NotFoundHttpException();
		}

		$settings = $service->getSettings();
		$title = 'Edit | Incoming Services';

		return view('admin.service.incoming.edit', compact('service', 'settings', 'title'));
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param string  $class
	 * @param \Illuminate\Http\Request $request
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function update($class, Request $request) {
		$service = $this->gatherer->incomingService($class);

		if (!$service) {
			throw new NotFoundHttpException();
		}

		$settings = $service->getSettings();
		if (!$settings) {
			$settings = new IncomingService();
			$settings->class = $service->id();
			$service->setSettings($settings);
		}

		// Set service options
		$this->validate($request, $service->serviceValidationRules());

		$settings->options = $request->get('options') ?: [];

		$settings->save();

		return redirect()->route('admin.service.incoming.index')->with('status', 'Service has been updated');
	}

}
