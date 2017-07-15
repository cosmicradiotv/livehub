<?php

namespace t2t2\LiveHub\Transformers;

use League\Fractal\TransformerAbstract;
use t2t2\LiveHub\Models\Show;

class ShowTransformer extends TransformerAbstract {

	public function transform(Show $show) {
		return [
			'id' => $show->id,
			'name' => $show->name,
			'slug' => $show->slug,
		];
	}

}
