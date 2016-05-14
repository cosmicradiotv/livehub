<?php
namespace t2t2\LiveHub\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use League\Fractal\Manager;
use League\Fractal\Resource\Collection as FractalCollection;
use t2t2\LiveHub\Models\Stream;
use t2t2\LiveHub\Transformers\StreamsTransformer;

class LiveController extends Controller
{


	/**
	 * @return Response
	 */
	public function home()
	{
		$best_stream = Stream::query()->orderBy('state', 'ASC')->orderBy('id', 'ASC')->first();

		return view('live.home', ['stream' => $best_stream]);
	}

	/**
	 * @return Response
	 */
	public function config(Manager $manager)
	{
		$settings = \Cache::remember('live.settings', 1, function () use ($manager) {
			$settings = [
				'brand' => config('livehub.brand'),
				'pushers' => [],
				'notlive' => route('helper.notlive'),
			];

			$settings['pushers'][] = [
				'type' => 'interval',
				'frequency' => 5 * 60,
				'target' => route('live.pusher.interval'),
			];

			$manager->parseIncludes(['show']);
			$settings['streams'] = $manager->createData($this->streams())->toArray();

			return $settings;
		});

		return JsonResponse::create($settings);
	}

	/**
	 * @return FractalCollection
	 */
	protected function streams()
	{
		$streams = Stream::all()->load('channel.service', 'show');

		return new FractalCollection($streams, new StreamsTransformer(), 'stream');
	}

	/**
	 * Give data for the interval based checker
	 *
	 * @return Response
	 */
	public function intervalPusher(Manager $manager)
	{
		$manager->parseIncludes(['show']);
		$streams = $manager->createData($this->streams())->toArray();

		return JsonResponse::create($streams);
	}
}
