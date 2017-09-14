<?php
namespace t2t2\LiveHub\Transformers;

use League\Fractal\TransformerAbstract;
use t2t2\LiveHub\Models\Stream;

class StreamsTransformer extends TransformerAbstract {

	/**
	 * Resources that can be included if requested.
	 *
	 * @var array
	 */
	protected $availableIncludes = [
		'show'
	];

	/**
	 * Turn the object into a generic array
	 *
	 * @param \t2t2\LiveHub\Models\Stream $stream
	 *
	 * @return array
	 */
	public function transform(Stream $stream) {
		$service = $stream->channel->service->getService();

		return [
			'id' => $stream->id,
			'channel_id' => $stream->channel_id,
			'show_id' => $stream->show_id,
			'title' => $stream->title,
			'state' => $stream->state,
			'service' => $service->name(),
			'start_time' => $stream->start_time->toIso8601String(),
			'url' => $stream->getUrl(),
			'video_url' => $stream->getVideoUrl(),
			'chat_url' => $stream->getChatUrl(),
		];
	}

	/**
	 * Include show data
	 *
	 * @param \t2t2\LiveHub\Models\Stream $stream
	 *
	 * @return \League\Fractal\Resource\Item
	 */
	public function includeShow(Stream $stream) {
		$show = $stream->show;

		return $this->item($show, new ShowTransformer(), 'show');
	}

}
