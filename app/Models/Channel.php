<?php
namespace t2t2\LiveHub\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;

/**
 * t2t2\LiveHub\Models\Channel
 *
 * @property integer                                                $id
 * @property integer                                                $incoming_service_id
 * @property string                                                 $name
 * @property array                                                  $options
 * @property string                                                 $video_url
 * @property string                                                 $chat_url
 * @property \Carbon\Carbon                                         $created_at
 * @property \Carbon\Carbon                                         $updated_at
 * @property-read \t2t2\LiveHub\Models\IncomingService              $service
 * @property-read \Illuminate\Database\Eloquent\Collection          $related[] $morphedByMany
 * @method static Builder|Channel whereId($value)
 * @method static Builder|Channel whereClass($value)
 * @method static Builder|Channel whereOptions($value)
 * @method static Builder|Channel whereCreatedAt($value)
 * @method static Builder|Channel whereUpdatedAt($value)
 * @method static Builder|Channel whereIncomingServiceId($value)
 * @method static Builder|Channel whereName($value)
 * @method static Builder|Channel whereVideoUrl($value)
 * @method static Builder|Channel whereChatUrl($value)
 * @property-read \Illuminate\Database\Eloquent\Collection|Stream[] $streams
 */
class Channel extends Model {

	protected $casts = ['options' => 'json'];

	protected $fillable = ['incoming_service_id', 'name', 'video_url', 'chat_url'];

	protected $hidden = ['options'];

	protected $table = 'channels';

	/**
	 * Get Chat URL for the channel
	 *
	 * @return string
	 */
	public function getChatUrl($stream = null) {
		return $this->chat_url ?: $this->service->getService()->getChatUrl($this, $stream);
	}

	/**
	 * Get Video URL for the channel
	 *
	 * @return string
	 */
	public function getVideoUrl($stream = null) {
		return $this->video_url ?: $this->service->getService()->getVideoUrl($this, $stream);
	}

	// Relations

	public function service() {
		return $this->belongsTo('t2t2\LiveHub\Models\IncomingService', 'incoming_service_id');
	}

	public function streams() {
		return $this->hasMany('t2t2\LiveHub\Models\Stream');
	}

}