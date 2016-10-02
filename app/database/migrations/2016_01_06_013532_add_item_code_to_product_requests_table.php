<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddItemCodeToProductRequestsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('product_requests', function(Blueprint $table)
		{
			$table->string('cataloguing_item_code')->nullable();
			$table->string('cataloguing_product_name')->nullable();
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
			$table->dropColumn('cataloguing_item_code');
			$table->dropColumn('cataloguing_product_name');
		});
	}

}
