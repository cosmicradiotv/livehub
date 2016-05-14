<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use t2t2\LiveHub\Models\Show;

class CreateShowsTable extends Migration
{

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		DB::transaction(function () {

			Schema::create('shows', function (Blueprint $table) {
				$table->increments('id');
				$table->string('name');
				$table->string('slug');
				$table->timestamps();
			});

			/** @var Show $default */
			$default = Show::create([
				'name' => 'Default Show',
				'slug' => 'show',
			]);

			Schema::table('streams', function (Blueprint $table) {
				$table->unsignedInteger('show_id')->after('channel_id')->nullable();
			});

			DB::table('streams')->update(['show_id' => $default->id]);

			Schema::table('streams', function (Blueprint $table) {
				$table->foreign('show_id')->references('id')->on('shows')->onUpdate('cascade')->onDelete('cascade');
			});

		});

	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		DB::transaction(function () {
			Schema::table('streams', function (Blueprint $table) {
				$table->dropForeign('streams_show_id_foreign');
				$table->dropColumn('show_id');
			});

			Schema::drop('shows');
		});
	}
}
