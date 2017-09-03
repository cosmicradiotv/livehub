<?php
namespace t2t2\LiveHub\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * t2t2\LiveHub\Models\Channel
 *
 * @property int $id
 * @property int $incoming_service_id
 * @property string $name
 * @property object $options
 * @property \Carbon\Carbon|null $last_checked
 * @property string|null $url
 * @property string|null $video_url
 * @property string|null $chat_url
 * @property int|null $default_show_id
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \t2t2\LiveHub\Models\Show|null $defaultShow
 * @property-read \t2t2\LiveHub\Models\IncomingService $service
 * @property-read \Illuminate\Database\Eloquent\Collection|\t2t2\LiveHub\Models\Show[] $shows
 * @property-read \Illuminate\Database\Eloquent\Collection|\t2t2\LiveHub\Models\Stream[] $streams
 * @method static \Illuminate\Database\Eloquent\Builder|\t2t2\LiveHub\Models\Channel whereChatUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\t2t2\LiveHub\Models\Channel whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\t2t2\LiveHub\Models\Channel whereDefaultShowId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\t2t2\LiveHub\Models\Channel whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\t2t2\LiveHub\Models\Channel whereIncomingServiceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\t2t2\LiveHub\Models\Channel whereLastChecked($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\t2t2\LiveHub\Models\Channel whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\t2t2\LiveHub\Models\Channel whereOptions($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\t2t2\LiveHub\Models\Channel whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\t2t2\LiveHub\Models\Channel whereUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\t2t2\LiveHub\Models\Channel whereVideoUrl($value)
 * @mixin \Eloquent
 */
class Channel extends Model {

	/**
	 * The attributes that should be cast to native types.
	 *
	 * @var array
	 */
	protected $casts = ['options' => 'object'];

	/**
	 * The attributes that should be mutated to dates.
	 *
	 * @var array
	 */
	protected $dates = ['last_checked'];

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = ['incoming_service_id', 'name', 'video_url', 'chat_url', 'default_show_id'];

	/**
	 * The attributes that should be hidden for arrays.
	 *
	 * @var array
	 */
	protected $hidden = ['options'];

	/**
	 * The table associated with the model.
	 *
	 * @var string
	 */
	protected $table = 'channels';

	/**
	 * Get the URL for the channel
	 *
	 * @param \t2t2\LiveHub\Models\Stream|null $stream
	 * @return string
	 */
	public function getUrl($stream = null) {
		return $this->url ?: $this->service->getService()->getUrl($this, $stream);
	}

	/**
	 * Get Chat URL for the channel
	 *
	 * @param \t2t2\LiveHub\Models\Stream|null $stream
	 * @return string
	 */
	public function getChatUrl($stream = null) {
		return $this->chat_url ?: $this->service->getService()->getChatUrl($this, $stream);
	}

	/**
	 * Get Video URL for the channel
	 *
	 * @param \t2t2\LiveHub\Models\Stream|null $stream
	 * @return string
	 */
	public function getVideoUrl($stream = null) {
		return $this->video_url ?: $this->service->getService()->getVideoUrl($this, $stream);
	}

	/**
	 * Update default_show_id, allowing for null
	 *
	 * @param int $value
	 * @return void
	 */
	public function setDefaultShowIdAttribute($value) {
		$this->attributes['default_show_id'] = $value ?: null;
	}

	// Relations

	public function service() {
		return $this->belongsTo('t2t2\LiveHub\Models\IncomingService', 'incoming_service_id');
	}

	/**
	 * Get the default show for channel's streams
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	 */
	public function defaultShow() {
		return $this->belongsTo('t2t2\LiveHub\Models\Show', 'default_show_id');
	}

	/**
	 * Show - Channels many-to-many relationship
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
	 */
	public function shows() {
		return $this->belongsToMany('t2t2\LiveHub\Models\Show')->withPivot('rules')->withTimestamps();
	}

	/**
	 * Channel - Streams one-to-many relationship
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\HasMany
	 */
	public function streams() {
		return $this->hasMany('t2t2\LiveHub\Models\Stream');
	}

}
