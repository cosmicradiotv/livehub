<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateChannelsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
		Schema::create('channels', function (Blueprint $table) {
			$table->increments('id');
			$table->unsignedInteger('incoming_service_id');
			$table->string('name');
			$table->text('options');
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
	public function down() {
		Schema::drop('channels');
	}

}
