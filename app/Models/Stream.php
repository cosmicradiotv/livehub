<?php
namespace t2t2\LiveHub\Models;

use Carbon\Carbon;
use DateTime;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;

/**
 * t2t2\LiveHub\Models\Stream
 *
 * @property integer                                        $id
 * @property integer                                        $channel_id
 * @property array                                          $service_info
 * @property string                                         $title
 * @property string                                         $state
 * @property \Carbon\Carbon                                 $start_time
 * @property string                                         $video_url
 * @property string                                         $chat_url
 * @property \Carbon\Carbon                                 $created_at
 * @property \Carbon\Carbon                                 $updated_at
 * @property-read \t2t2\LiveHub\Models\Channel              $channel
 * @property-read \Illuminate\Database\Eloquent\Collection|\$related[] $morphedByMany
 * @method static Builder|Stream whereId($value)
 * @method static Builder|Stream whereChannelId($value)
 * @method static Builder|Stream whereServiceInfo($value)
 * @method static Builder|Stream whereTitle($value)
 * @method static Builder|Stream whereState($value)
 * @method static Builder|Stream whereStartTime($value)
 * @method static Builder|Stream whereVideoUrl($value)
 * @method static Builder|Stream whereChatUrl($value)
 * @method static Builder|Stream whereCreatedAt($value)
 * @method static Builder|Stream whereUpdatedAt($value)
 * @property integer $show_id 
 * @property-read \t2t2\LiveHub\Models\Show $show 
 * @method static \Illuminate\Database\Query\Builder|\t2t2\LiveHub\Models\Stream whereShowId($value)
 */
class Stream extends Model {

	protected $dates = ['start_time'];

	protected $fillable = ['channel_id', 'show_id', 'service_info', 'title', 'state', 'start_time', 'video_url', 'chat_url'];

	protected $hidden = ['service_info'];

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
	public function show(){
		return $this->belongsTo('t2t2\LiveHub\Models\Show');
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
		if(! ($value instanceof DateTime)) {
			$value = Carbon::parse($value);
		}
		$this->attributes['start_time'] = $value;
	}

}