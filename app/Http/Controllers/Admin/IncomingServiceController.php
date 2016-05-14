<?php namespace t2t2\LiveHub\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use t2t2\LiveHub\Http\Requests;
use t2t2\LiveHub\Models\IncomingService;
use t2t2\LiveHub\Services\Incoming\Service;
use t2t2\LiveHub\Services\ServicesGatherer;

class IncomingServiceController extends AdminController
{

	/**
	 * @var ServicesGatherer
	 */
	private $gatherer;


	/**
	 * @param ServicesGatherer $gatherer
	 */
	public function __construct(ServicesGatherer $gatherer)
    {
		parent::__construct();

		$this->gatherer = $gatherer;
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
    {

		/** @var Service[] $services */
		$services = $this->gatherer->allIncomingServices();

		$title = 'Incoming Services';

		return view('admin.service.incoming.index', compact('services', 'title'));
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  string $class
	 *
	 * @return Response
	 */
	public function edit($class)
    {
		$service = $this->gatherer->incomingService($class);

		if (! $service) {
			throw new NotFoundHttpException;
		}

		$settings = $service->getSettings();
		$title = 'Edit | Incoming Services';

		return view('admin.service.incoming.edit', compact('service', 'settings', 'title'));
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param string  $class
	 * @param Request $request
	 *
	 * @return Response
	 */
	public function update($class, Request $request)
    {
		$service = $this->gatherer->incomingService($class);

		if (! $service) {
			throw new NotFoundHttpException;
		}

		$settings = $service->getSettings();
		if (! $settings) {
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
