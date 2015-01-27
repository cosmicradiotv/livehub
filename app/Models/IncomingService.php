<?php
namespace t2t2\LiveHub\Models;

use Illuminate\Database\Eloquent\Model;
use t2t2\LiveHub\Services\Incoming\Service;

/**
 * t2t2\LiveHub\Models\IncomingService
 *
 * @property integer                                                                      $id
 * @property string                                                                       $class
 * @property array                                                                        $options
 * @property \Carbon\Carbon                                                               $created_at
 * @property \Carbon\Carbon                                                               $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\t2t2\LiveHub\Models\Channel[] $channels
 * @property-read \Illuminate\Database\Eloquent\Collection|\                              $related[] $morphedByMany
 * @method static \Illuminate\Database\Query\Builder|\t2t2\LiveHub\Models\IncomingService whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\t2t2\LiveHub\Models\IncomingService whereClass($value)
 * @method static \Illuminate\Database\Query\Builder|\t2t2\LiveHub\Models\IncomingService whereOptions($value)
 * @method static \Illuminate\Database\Query\Builder|\t2t2\LiveHub\Models\IncomingService whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\t2t2\LiveHub\Models\IncomingService whereUpdatedAt($value)
 * @property-read \Illuminate\Database\Eloquent\Collection                                $morphedByMany
 */
class IncomingService extends Model {

	protected $casts = ['options' => 'object'];

	protected $fillable = ['class'];

	protected $hidden = ['options'];

	protected $table = 'incoming_services';

	// Relations

	public function channels() {
		return $this->hasMany('t2t2\LiveHub\Models\Channel', 'incoming_service_id');
	}

	/**
	 * Get the service instance for this class
	 *
	 * @return Service
	 */
	public function getService() {
		/** @var Service $class */
		$class = app("livehub.services.incoming.{$this->class}");

		$class->setSettings($this);

		return $class;
	}
}