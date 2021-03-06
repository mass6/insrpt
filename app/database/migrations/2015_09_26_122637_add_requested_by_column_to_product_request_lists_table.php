<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddRequestedByColumnToProductRequestListsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('product_request_lists', function(Blueprint $table)
		{
			$table->integer('requested_by_id')->unsigned()->after('created_by_id');
            $table->foreign('requested_by_id')->references('id')->on('users');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('product_request_lists', function(Blueprint $table)
		{
			$table->dropForeign('product_request_lists_requested_by_id_foreign');
			$table->dropColumn('requested_by_id');
		});
	}

}
