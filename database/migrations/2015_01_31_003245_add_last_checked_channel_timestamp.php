<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddLastCheckedChannelTimestamp extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
		Schema::table('channels', function (Blueprint $table) {
			$table->timestamp('last_checked')->after('options')->nullable();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down() {
		Schema::table('channels', function (Blueprint $table) {
			$table->dropColumn('last_checked');
		});
	}

}
