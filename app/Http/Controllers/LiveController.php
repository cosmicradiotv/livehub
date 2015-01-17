<?php
namespace t2t2\LiveHub\Http\Controllers;


use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use League\Fractal\Manager;
use League\Fractal\Resource\Collection as FractalCollection;
use t2t2\LiveHub\Models\Stream;
use t2t2\LiveHub\Transformers\StreamsTransformer;

class LiveController extends Controller {


	/**
	 * @return Response
	 */
	public function home() {
		return view('live.home');
	}

	/**
	 * @return Response
	 */
	public function config(Manager $manager) {
		$settings = [
			'pushers' => [],
		];

		$settings['pushers']['interval'] = [
			'frequency' => 5 * 60,
		];

		$settings['streams'] = $manager->createData($this->streams())->toArray();


		return JsonResponse::create($settings);
	}

	/**
	 * @return FractalCollection
	 */
	protected function streams() {
		$streams = Stream::all()->load('channel.service');

		return new FractalCollection($streams, new StreamsTransformer(), 'stream');
	}
}