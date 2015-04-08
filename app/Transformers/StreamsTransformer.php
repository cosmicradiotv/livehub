<?php
namespace t2t2\LiveHub\Transformers;

use League\Fractal\TransformerAbstract;
use t2t2\LiveHub\Models\Stream;

class StreamsTransformer extends TransformerAbstract {

	protected $availableIncludes = [
		'show'
	];

	/**
	 * Turn the object into a generic array
	 *
	 * @param Stream $stream
	 *
	 * @return array
	 */
	public function transform(Stream $stream) {
		return [
			'id' => $stream->id,
			'show_id' => $stream->show_id,
			'title' => $stream->title,
			'state' => $stream->state,
			'start_time' => $stream->start_time->toIso8601String(),
			'video_url' => $stream->getVideoUrl(),
			'chat_url' => $stream->getChatUrl(),
		];
	}

	/**
	 * Include show data
	 *
	 * @param Stream $stream
	 *
	 * @return \League\Fractal\Resource\Item
	 */
	public function includeShow(Stream $stream) {
		$show = $stream->show;

		return $this->item($show, new ShowTransformer, 'show');
	}

}