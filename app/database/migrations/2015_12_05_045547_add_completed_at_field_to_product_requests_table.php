<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCompletedAtFieldToProductRequestsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('product_requests', function(Blueprint $table)
		{
			$table->timestamp('completed_at')->nullable()->after('updated_by_id');
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
			$table->dropColumn('completed_at');
		});
	}

}
