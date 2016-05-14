<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class StreamLookupHelpingField extends Migration
{

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		DB::transaction(function () {
			Schema::table('streams', function (Blueprint $table) {
				$table->dropColumn('options');
			}); // Separated due to sqlite problems

			Schema::table('streams', function (Blueprint $table) {
				$table->string('service_info')->after('channel_id')->nullable()->index();
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
				$table->dropColumn('service_info');
			});
			Schema::table('streams', function (Blueprint $table) {
				$table->json('options')->after('channel_id')->nullable();
			});
		});
	}
}
