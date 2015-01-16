<?php
namespace t2t2\LiveHub\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * t2t2\LiveHub\Models\Channel
 *
 * @property integer                                        $id
 * @property integer                                        $incoming_service_id
 * @property string                                         $name
 * @property array                                          $options
 * @property string                                         $video_url
 * @property string                                         $chat_url
 * @property \Carbon\Carbon                                 $created_at
 * @property \Carbon\Carbon                                 $updated_at
 * @property-read \t2t2\LiveHub\Models\IncomingService      $service
 * @property-read \Illuminate\Database\Eloquent\Collection|\$related[] $morphedByMany
 * @method static \Illuminate\Database\Query\Builder|\t2t2\LiveHub\Models\Channel whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\t2t2\LiveHub\Models\Channel whereClass($value)
 * @method static \Illuminate\Database\Query\Builder|\t2t2\LiveHub\Models\Channel whereOptions($value)
 * @method static \Illuminate\Database\Query\Builder|\t2t2\LiveHub\Models\Channel whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\t2t2\LiveHub\Models\Channel whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\t2t2\LiveHub\Models\Channel whereIncomingServiceId($value)
 * @method static \Illuminate\Database\Query\Builder|\t2t2\LiveHub\Models\Channel whereName($value)
 * @method static \Illuminate\Database\Query\Builder|\t2t2\LiveHub\Models\Channel whereVideoUrl($value)
 * @method static \Illuminate\Database\Query\Builder|\t2t2\LiveHub\Models\Channel whereChatUrl($value)
 */
class Channel extends Model {

	protected $casts = ['options' => 'json'];

	protected $fillable = ['incoming_service_id', 'name', 'video_url', 'chat_url'];

	protected $hidden = ['options'];

	protected $table = 'channels';

	// Relations

	public function service() {
		return $this->belongsTo('t2t2\LiveHub\Models\IncomingService', 'incoming_service_id');
	}

}