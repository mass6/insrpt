<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddUserIdToProductAttachmentsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('product_attachments', function(Blueprint $table)
		{
			$table->integer('user_id')->unsigned()->nullable();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('product_attachments', function(Blueprint $table)
		{
			$table->dropColumn('user_id');
		});
	}

}
