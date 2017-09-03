<?php
namespace t2t2\LiveHub\Models;

use Carbon\Carbon;
use DateTime;
use Illuminate\Database\Eloquent\Model;

/**
 * t2t2\LiveHub\Models\Stream
 *
 * @property int $id
 * @property int $channel_id
 * @property int|null $show_id
 * @property string|null $service_info
 * @property string $title
 * @property string $state
 * @property \Carbon\Carbon $start_time
 * @property string|null $url
 * @property string|null $video_url
 * @property string|null $chat_url
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \t2t2\LiveHub\Models\Channel $channel
 * @property-read \t2t2\LiveHub\Models\Show|null $show
 * @method static \Illuminate\Database\Eloquent\Builder|\t2t2\LiveHub\Models\Stream whereChannelId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\t2t2\LiveHub\Models\Stream whereChatUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\t2t2\LiveHub\Models\Stream whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\t2t2\LiveHub\Models\Stream whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\t2t2\LiveHub\Models\Stream whereServiceInfo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\t2t2\LiveHub\Models\Stream whereShowId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\t2t2\LiveHub\Models\Stream whereStartTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\t2t2\LiveHub\Models\Stream whereState($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\t2t2\LiveHub\Models\Stream whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\t2t2\LiveHub\Models\Stream whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\t2t2\LiveHub\Models\Stream whereUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\t2t2\LiveHub\Models\Stream whereVideoUrl($value)
 * @mixin \Eloquent
 */
class Stream extends Model {

	/**
	 * The attributes that should be mutated to dates.
	 *
	 * @var array
	 */
	protected $dates = ['start_time'];

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [
		'channel_id',
		'show_id',
		'service_info',
		'title',
		'state',
		'start_time',
		'video_url',
		'chat_url'
	];

	/**
	 * The attributes that should be hidden for arrays.
	 *
	 * @var array
	 */
	protected $hidden = ['service_info'];

	/**
	 * The table associated with the model.
	 *
	 * @var string
	 */
	protected $table = 'streams';

	/**
	 * Channel relation
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	 */
	public function channel() {
		return $this->belongsTo('t2t2\LiveHub\Models\Channel');
	}

	/**
	 * Show relation
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	 */
	public function show() {
		return $this->belongsTo('t2t2\LiveHub\Models\Show');
	}

	/**
	 * Gets the URL for the stream
	 *
	 * @return string
	 */
	public function getUrl() {
		return $this->url ?: $this->channel->getUrl($this);
	}

	/**
	 * Gets the chat embed url for the stream
	 *
	 * @return string
	 */
	public function getChatUrl() {
		return $this->chat_url ?: $this->channel->getChatUrl($this);
	}

	/**
	 * Gets the video embed url for the stream
	 *
	 * @return string
	 */
	public function getVideoUrl() {
		return $this->video_url ?: $this->channel->getVideoUrl($this);
	}

	// Setters
	public function setStartTimeAttribute($value) {
		if (!($value instanceof DateTime)) {
			$value = Carbon::parse($value);
		}
		$this->attributes['start_time'] = $value;
	}

}
