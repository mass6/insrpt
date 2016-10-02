<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDeliveryEmailToQuotationRequestsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('quotation_requests', function(Blueprint $table)
		{
			$table->string('delivery_email')->nullable()->after('sent');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('quotation_requests', function(Blueprint $table)
		{
			$table->dropColumn('delivery_email');
		});
	}

}
