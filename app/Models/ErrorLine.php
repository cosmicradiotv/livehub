<?php

namespace t2t2\LiveHub\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * t2t2\LiveHub\Models\ErrorLine
 *
 * @property integer $id
 * @property string $text
 * @property integer $channel_id
 * @property \Carbon\Carbon $created_at
 * @property-read \t2t2\LiveHub\Models\Channel $channel
 * @property-read \Illuminate\Database\Eloquent\Collection|\$related[] $morphedByMany
 * @method static \Illuminate\Database\Query\Builder|\t2t2\LiveHub\Models\ErrorLine whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\t2t2\LiveHub\Models\ErrorLine whereText($value)
 * @method static \Illuminate\Database\Query\Builder|\t2t2\LiveHub\Models\ErrorLine whereChannelId($value)
 * @method static \Illuminate\Database\Query\Builder|\t2t2\LiveHub\Models\ErrorLine whereCreatedAt($value)
 */
class ErrorLine extends Model {

	/**
	 * The table associated with the model.
	 *
	 * @var string
	 */
	protected $table = 'errors';

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = ['text', 'channel_id', 'created_at'];

	/**
	 * The attributes that should be mutated to dates.
	 *
	 * @var array
	 */
	protected $dates = ['created_at'];

	/**
	 * Indicates if the model should be timestamped.
	 *
	 * @var bool
	 */
	public $timestamps = false;

	// Relations

	public function channel() {
		return $this->belongsTo('t2t2\LiveHub\Models\Channel');
	}

}
