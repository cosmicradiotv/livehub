<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStreamsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('streams', function(Blueprint $table)
		{
			$table->increments('id');
			$table->unsignedInteger('channel_id');
			$table->json('options');
			$table->string('title');
			$table->string('state');
			$table->dateTime('start_time');
			$table->text('video_url')->nullable();
			$table->text('chat_url')->nullable();
			$table->timestamps();

			$table->foreign('channel_id')->references('id')->on('channels')
			      ->onUpdate('cascade')->onDelete('cascade');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('streams');
	}

}
