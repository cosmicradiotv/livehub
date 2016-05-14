<?php
namespace t2t2\LiveHub\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;
use t2t2\LiveHub\Services\Incoming\Service;

/**
 * t2t2\LiveHub\Models\IncomingService
 *
 * @property integer                                                                      $id
 * @property string                                                                       $class
 * @property object                                                                       $options
 * @property \Carbon\Carbon                                                               $created_at
 * @property \Carbon\Carbon                                                               $updated_at
 * @property-read Collection|\t2t2\LiveHub\Models\Channel[]                               $channels
 * @method static Builder|IncomingService whereId($value)
 * @method static Builder|IncomingService whereClass($value)
 * @method static Builder|IncomingService whereOptions($value)
 * @method static Builder|IncomingService whereCreatedAt($value)
 * @method static Builder|IncomingService whereUpdatedAt($value)
 */
class IncomingService extends Model
{

	protected $casts = ['options' => 'object'];

	protected $fillable = ['class'];

	protected $hidden = ['options'];

	protected $table = 'incoming_services';

	// Relations

	public function channels()
    {
		return $this->hasMany('t2t2\LiveHub\Models\Channel', 'incoming_service_id');
	}

	/**
	 * Get the service instance for this class
	 *
	 * @return Service
	 */
	public function getService()
    {
		/** @var Service $class */
		$class = app("livehub.services.incoming.{$this->class}");

		$class->setSettings($this);

		return $class;
	}
}
