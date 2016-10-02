<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddApprovalFieldsToProposalsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('product_proposals', function(Blueprint $table)
		{
			$table->integer('approved_by_id')->unsigned()->nullable()->after('num_approvals');
            $table->timestamp('approved_at')->nullable()->after('approved_by_id');
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
			$table->dropColumn('approved_by_id');
			$table->dropColumn('approved_at');
		});
	}

}
