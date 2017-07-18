<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateChannelShowsPivot extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
		Schema::create('channel_show', function (Blueprint $table) {

			$table->integer('channel_id')->unsigned();
			$table->foreign('channel_id')->references('id')->on('channels')->onDelete('cascade');
			$table->integer('show_id')->unsigned();
			$table->foreign('show_id')->references('id')->on('shows')->onDelete('cascade');

			$table->text('rules');
			$table->timestamps();

		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down() {
		Schema::drop('channel_show');
	}

}
