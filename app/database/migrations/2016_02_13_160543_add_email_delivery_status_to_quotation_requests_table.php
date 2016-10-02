<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddEmailDeliveryStatusToQuotationRequestsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('quotation_requests', function(Blueprint $table)
		{
			$table->string('email_delivery_status')->nullable()->after('delivery_email');
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
			$table->dropColumn('email_delivery_status');
		});
	}

}
