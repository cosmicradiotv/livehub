<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDefaultShowToChannel extends Migration
{

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('channels', function (Blueprint $table) {
			$table->integer('default_show_id')->unsigned()->nullable()->after('chat_url');
			$table->foreign('default_show_id')->references('id')->on('shows')->onDelete('set null');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('channels', function (Blueprint $table) {
			$table->dropForeign('channels_default_show_id_foreign');
			$table->dropColumn('default_show_id');
		});
	}
}
