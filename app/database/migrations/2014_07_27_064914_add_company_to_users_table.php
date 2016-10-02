<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCompanyToUsersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('users', function(Blueprint $table)
		{
            $table->integer('company_id')->unsigned()->nullable()->after('permissions');

            $table->foreign('company_id')->references('id')->on('companies');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('users', function(Blueprint $table)
		{
            $table->dropForeign('users_company_id_foreign');
			$table->dropColumn('company_id');
		});
	}

}
