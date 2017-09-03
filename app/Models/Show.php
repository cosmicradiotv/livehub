<?php namespace t2t2\LiveHub\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * t2t2\LiveHub\Models\Show
 *
 * @property int $id
 * @property string $name
 * @property string $slug
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\t2t2\LiveHub\Models\Channel[] $channels
 * @property-read \Illuminate\Database\Eloquent\Collection|\t2t2\LiveHub\Models\Channel[] $defaultFor
 * @method static \Illuminate\Database\Eloquent\Builder|\t2t2\LiveHub\Models\Show whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\t2t2\LiveHub\Models\Show whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\t2t2\LiveHub\Models\Show whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\t2t2\LiveHub\Models\Show whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\t2t2\LiveHub\Models\Show whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Show extends Model {

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = ['name', 'slug', 'default'];

	/**
	 * The table associated with the model.
	 *
	 * @var string
	 */
	protected $table = 'shows';

	/**
	 * Show - Channels many-to-many relationship. Doesn't include which it's default for.
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
	 */
	public function channels() {
		return $this->belongsToMany('t2t2\LiveHub\Models\Channel')->withPivot('rules')->withTimestamps();
	}

	/**
	 * Channels this show is default for
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\HasMany
	 */
	public function defaultFor() {
		return $this->hasMany('t2t2\LiveHub\Models\Channel', 'default_show_id');
	}

}
