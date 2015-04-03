<?php
namespace t2t2\LiveHub\Transformers;

use League\Fractal\TransformerAbstract;
use t2t2\LiveHub\Models\Stream;

class StreamsTransformer extends TransformerAbstract {

	public function transform(Stream $stream) {
		return [
			'id' => $stream->id,
			'title' => $stream->title,
			'state' => $stream->state,
			'start_time' => $stream->start_time->toIso8601String(),
			'video_url' => $stream->getVideoUrl(),
			'chat_url' => $stream->getChatUrl(),
		];
	}

}