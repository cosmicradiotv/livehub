<?php namespace t2t2\LiveHub\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;

/**
 * t2t2\LiveHub\Models\User
 *
 * @property-read \Illuminate\Database\Eloquent\Collection|\$related[] $morphedByMany
 * @property integer $id
 * @property string $username
 * @property string $email
 * @property string $password
 * @property string $remember_token
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @method static \Illuminate\Database\Query\Builder|\t2t2\LiveHub\Models\User whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\t2t2\LiveHub\Models\User whereUsername($value)
 * @method static \Illuminate\Database\Query\Builder|\t2t2\LiveHub\Models\User whereEmail($value)
 * @method static \Illuminate\Database\Query\Builder|\t2t2\LiveHub\Models\User wherePassword($value)
 * @method static \Illuminate\Database\Query\Builder|\t2t2\LiveHub\Models\User whereRememberToken($value)
 * @method static \Illuminate\Database\Query\Builder|\t2t2\LiveHub\Models\User whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\t2t2\LiveHub\Models\User whereUpdatedAt($value)
 */
class User extends Model implements AuthenticatableContract, CanResetPasswordContract {

	use Authenticatable, CanResetPassword;

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'users';

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = ['username', 'email', 'password'];

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	protected $hidden = ['password', 'remember_token'];

}
