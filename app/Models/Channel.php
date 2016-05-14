<?php
namespace t2t2\LiveHub\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;

/**
 * t2t2\LiveHub\Models\Channel
 *
 * @property integer                                                $id
 * @property integer                                                $incoming_service_id
 * @property string                                                 $name
 * @property object                                                 $options
 * @property \Carbon\Carbon                                         $last_checked
 * @property string                                                 $video_url
 * @property string                                                 $chat_url
 * @property integer                                                $default_show_id
 * @property \Carbon\Carbon                                         $created_at
 * @property \Carbon\Carbon                                         $updated_at
 * @property-read Show                                              $defaultShow
 * @property-read IncomingService                                   $service
 * @property-read Collection|Show[]                                 $shows
 * @property-read Collection|Stream[]                               $streams
 * @method static Builder|Channel whereId($value)
 * @method static Builder|Channel whereIncomingServiceId($value)
 * @method static Builder|Channel whereName($value)
 * @method static Builder|Channel whereOptions($value)
 * @method static Builder|Channel whereLastChecked($value)
 * @method static Builder|Channel whereVideoUrl($value)
 * @method static Builder|Channel whereChatUrl($value)
 * @method static Builder|Channel whereDefaultShowId($value)
 * @method static Builder|Channel whereCreatedAt($value)
 * @method static Builder|Channel whereUpdatedAt($value)
 */
class Channel extends Model
{

	protected $casts = ['options' => 'object'];

	protected $dates = ['last_checked'];

	protected $fillable = ['incoming_service_id', 'name', 'video_url', 'chat_url', 'default_show_id'];

	protected $hidden = ['options'];

	protected $table = 'channels';

	/**
	 * Get Chat URL for the channel
	 *
	 * @return string
	 */
	public function getChatUrl($stream = null)
    {
		return $this->chat_url ?: $this->service->getService()->getChatUrl($this, $stream);
	}

	/**
	 * Get Video URL for the channel
	 *
	 * @return string
	 */
	public function getVideoUrl($stream = null)
    {
		return $this->video_url ?: $this->service->getService()->getVideoUrl($this, $stream);
	}

	/**
	 * Update default_show_id, allowing for null
	 *
	 * @param $value
	 */
	public function setDefaultShowIdAttribute($value)
    {
		$this->attributes['default_show_id'] = $value ?: null;
	}

	// Relations

	public function service()
    {
		return $this->belongsTo('t2t2\LiveHub\Models\IncomingService', 'incoming_service_id');
	}

	/**
	 * Get the default show for channel's streams
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	 */
	public function defaultShow()
    {
		return $this->belongsTo('t2t2\LiveHub\Models\Show', 'default_show_id');
	}

	/**
	 * Show - Channels many-to-many relationship
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
	 */
	public function shows()
    {
		return $this->belongsToMany('t2t2\LiveHub\Models\Show')->withPivot('rules')->withTimestamps();
	}

	/**
	 * Channel - Streams one-to-many relationship
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\HasMany
	 */
	public function streams()
    {
		return $this->hasMany('t2t2\LiveHub\Models\Stream');
	}
}
