<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFirstTimeOrderQuantityFieldToProductRequestsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('product_requests', function(Blueprint $table)
		{
			$table->integer('first_time_order_quantity')->unsigned()->nullable()->after('purchase_recurrence');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('product_requests', function(Blueprint $table)
		{
			$table->dropColumn('first_time_order_quantity');
		});
	}

}
