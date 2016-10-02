<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAutoApprovedColumnToProductProposalsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('product_proposals', function(Blueprint $table)
		{
			$table->boolean('auto_approved')->nullable()->after('approved_at');
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
			$table->dropColumn('auto_approved');
		});
	}

}
