<?php
namespace t2t2\LiveHub\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * t2t2\LiveHub\Models\IncomingService
 *
 * @property int $id
 * @property string $class
 * @property object $options
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\t2t2\LiveHub\Models\Channel[] $channels
 * @method static \Illuminate\Database\Eloquent\Builder|\t2t2\LiveHub\Models\IncomingService whereClass($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\t2t2\LiveHub\Models\IncomingService whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\t2t2\LiveHub\Models\IncomingService whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\t2t2\LiveHub\Models\IncomingService whereOptions($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\t2t2\LiveHub\Models\IncomingService whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class IncomingService extends Model {

	/**
	 * The attributes that should be cast to native types.
	 *
	 * @var array
	 */
	protected $casts = ['options' => 'object'];

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = ['class'];

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
	protected $table = 'incoming_services';

	// Relations

	public function channels() {
		return $this->hasMany('t2t2\LiveHub\Models\Channel', 'incoming_service_id');
	}

	/**
	 * Get the service instance for this class
	 *
	 * @return \t2t2\LiveHub\Services\Incoming\Service
	 */
	public function getService() {
		/* @var \t2t2\LiveHub\Services\Incoming\Service $class */
		$class = app("livehub.services.incoming.{$this->class}");

		$class->setSettings($this);

		return $class;
	}

}
