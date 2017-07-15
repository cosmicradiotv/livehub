<?php namespace t2t2\LiveHub\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * t2t2\LiveHub\Models\Show
 *
 * @property integer                   $id
 * @property string                    $name
 * @property string                    $slug
 * @property \Carbon\Carbon            $created_at
 * @property \Carbon\Carbon            $updated_at
 * @property-read Collection|Channel[] $channels
 * @property-read Channel|null $defaultFor
 * @method static Builder|Show whereId($value)
 * @method static Builder|Show whereName($value)
 * @method static Builder|Show whereSlug($value)
 * @method static Builder|Show whereCreatedAt($value)
 * @method static Builder|Show whereUpdatedAt($value)
 * @property-read \Illuminate\Database\Eloquent\Collection|\$related[] $morphedByMany
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
