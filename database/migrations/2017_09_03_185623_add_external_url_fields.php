<?php

use Illuminate\Database\Migrations\Migration;

class AddExternalUrlFields extends Migration {
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
		Schema::table('streams', function($table) {
			$table->text('url')->after('start_time')->nullable();
		});
		Schema::table('channels', function($table) {
			$table->text('url')->after('last_checked')->nullable();
		});
	}

	/**
	* Reverse the migrations.
	*
	* @return void
	*/
	public function down() {
		Schema::table('channels', function($table) {
			$table->dropColumn('url');
		});
		Schema::table('streams', function($table) {
			$table->dropColumn('url');
		});
	}

}
