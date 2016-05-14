<?php
namespace t2t2\LiveHub\Services;

use Illuminate\Contracts\Container\Container;
use Illuminate\Support\Collection;
use t2t2\LiveHub\Models\IncomingService;
use t2t2\LiveHub\Services\Incoming\Service;

class ServicesGatherer
{

	/**
	 * @var Container
	 */
	private $app;

	/**
	 * @param Container $app
	 */
	public function __construct(Container $app)
	{
		$this->app = $app;
	}

	/**
	 * Return all services, fully configured
	 *
	 * @return Collection|Service[]
	 */
	public function allIncomingServices()
	{
		/** @var Service[] $classes */
		$classes = $this->app->tagged('livehub.services.incoming');
		$settings = IncomingService::all()->keyBy('class');

		$returns = new Collection();
		// Attach settings to classes
		foreach ($classes as $class) {
			$class->setSettings($settings->get($class->id()));
			$returns->put($class->id(), $class);
		}

		return $returns;
	}

	/**
	 * Find service class by class name
	 *
	 * @param $class
	 *
	 * @return null|Service
	 */
	public function incomingService($class)
	{
		/** @var Service $class */
		$class = $this->app->make("livehub.services.incoming.{$class}");
		if (!$class) {
			return null;
		}

		$class->setSettings(IncomingService::whereClass(class_basename($class))->first());
		return $class;
	}
}
