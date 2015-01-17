<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class StreamLookupHelpingField extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('streams', function(Blueprint $table) {
			$table->dropColumn('options');

			$table->string('service_info')->after('channel_id')->index()->nullable();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('streams', function(Blueprint $table) {
			$table->dropColumn('service_info')->after('channel_id');

			$table->json('options');
		});
	}

}
