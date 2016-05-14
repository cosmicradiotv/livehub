<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateChannelsTable extends Migration
{

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('channels', function (Blueprint $table) {
			$table->increments('id');
			$table->unsignedInteger('incoming_service_id');
			$table->string('name');
			$table->json('options');
			$table->text('video_url')->nullable();
			$table->text('chat_url')->nullable();
			$table->timestamps();

			$table->foreign('incoming_service_id')->references('id')->on('incoming_services')
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
		Schema::drop('channels');
	}
}
