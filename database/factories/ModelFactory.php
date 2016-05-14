<?php
use Faker\Generator;
use Illuminate\Database\Eloquent\Factory;

/** @var Factory $factory */

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

$factory->define(\t2t2\LiveHub\Models\User::class, function (Generator $faker) {
	return [
		'username' => $faker->userName,
		'email' => $faker->safeEmail,
		'password' => bcrypt(str_random(10)),
		'remember_token' => str_random(10),
	];
});
