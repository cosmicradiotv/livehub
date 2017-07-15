<?php
namespace t2t2\LiveHub\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * t2t2\LiveHub\Models\IncomingService
 *
 * @property integer                                                                      $id
 * @property string                                                                       $class
 * @property object                                                                       $options
 * @property \Carbon\Carbon                                                               $created_at
 * @property \Carbon\Carbon                                                               $updated_at
 * @property-read Collection|\t2t2\LiveHub\Models\Channel[] $channels
 * @method static Builder|IncomingService whereId($value)
 * @method static Builder|IncomingService whereClass($value)
 * @method static Builder|IncomingService whereOptions($value)
 * @method static Builder|IncomingService whereCreatedAt($value)
 * @method static Builder|IncomingService whereUpdatedAt($value)
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
