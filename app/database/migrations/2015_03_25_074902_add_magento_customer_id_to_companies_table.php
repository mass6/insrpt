<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddMagentoCustomerIdToCompaniesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        Schema::table('companies', function($table)
        {
            $table->string('magento_customer_group_id')->after('type')->nullable();
        });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('companies', function(Blueprint $table)
		{
			$table->dropColumn('magento_customer_group_id');
		});
	}

}
