<?php namespace t2t2\LiveHub\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use t2t2\LiveHub\Notifications\ResetPasswordNotification;

/**
 * t2t2\LiveHub\Models\User
 *
 * @property-read \Illuminate\Database\Eloquent\Collection|\ $related[] $morphedByMany
 * @property integer $id
 * @property string $username
 * @property string $email
 * @property string $password
 * @property string $remember_token
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @method static Builder|User whereId($value)
 * @method static Builder|User whereUsername($value)
 * @method static Builder|User whereEmail($value)
 * @method static Builder|User wherePassword($value)
 * @method static Builder|User whereRememberToken($value)
 * @method static Builder|User whereCreatedAt($value)
 * @method static Builder|User whereUpdatedAt($value)
 */
class User extends Authenticatable {
	use Notifiable;

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

	/**
	* Send the password reset notification.
	*
	* @param  string  $token
	* @return void
	*/
	public function sendPasswordResetNotification($token) {
		$this->notify(new ResetPasswordNotification($token));
	}

}
