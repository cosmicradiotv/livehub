<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddLastCheckedChannelTimestamp extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
		Schema::table('channels', function(Blueprint $table) {
			$table->timestamp('last_checked')->after('options');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down() {
		Schema::table('channels', function(Blueprint $table) {
			$table->dropColumn('last_checked');
		});
	}

}
