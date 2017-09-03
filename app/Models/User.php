<?php namespace t2t2\LiveHub\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use t2t2\LiveHub\Notifications\ResetPasswordNotification;

/**
 * t2t2\LiveHub\Models\User
 *
 * @property int $id
 * @property string $username
 * @property string $email
 * @property string $password
 * @property string|null $remember_token
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @method static \Illuminate\Database\Eloquent\Builder|\t2t2\LiveHub\Models\User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\t2t2\LiveHub\Models\User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\t2t2\LiveHub\Models\User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\t2t2\LiveHub\Models\User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\t2t2\LiveHub\Models\User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\t2t2\LiveHub\Models\User whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\t2t2\LiveHub\Models\User whereUsername($value)
 * @mixin \Eloquent
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
