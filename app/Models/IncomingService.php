<?php
namespace t2t2\LiveHub\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * t2t2\LiveHub\Models\IncomingService
 *
 * @property integer                                        $id
 * @property string                                         $class
 * @property array                                          $options
 * @property \Carbon\Carbon                                 $created_at
 * @property \Carbon\Carbon                                 $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\$related[] $morphedByMany
 * @method static \Illuminate\Database\Query\Builder|\t2t2\LiveHub\Models\IncomingService whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\t2t2\LiveHub\Models\IncomingService whereClass($value)
 * @method static \Illuminate\Database\Query\Builder|\t2t2\LiveHub\Models\IncomingService whereOptions($value)
 * @method static \Illuminate\Database\Query\Builder|\t2t2\LiveHub\Models\IncomingService whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\t2t2\LiveHub\Models\IncomingService whereUpdatedAt($value)
 */
class IncomingService extends Model {

	protected $table = 'incoming_services';

	protected $casts = ['options' => 'json'];

}