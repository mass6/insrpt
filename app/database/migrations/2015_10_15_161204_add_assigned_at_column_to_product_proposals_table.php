<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAssignedAtColumnToProductProposalsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('product_proposals', function(Blueprint $table)
		{
			$table->timestamp('assigned_at')->nullable();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('product_proposals', function(Blueprint $table)
		{
			$table->dropColumn('assigned_at');
		});
	}

}
