<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSuppliersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('suppliers', function(Blueprint $table)
		{
			$table->increments('id');
            $table->integer('company_id')->unsigned();
			$table->string('name');
            $table->text('address')->nullable();
            $table->string('email')->nullable();
            $table->string('website')->nullable();
            $table->string('primary_contact')->nullable();
            $table->string('telephone1')->nullable();
            $table->string('telephone2')->nullable();
            $table->string('fax')->nullable();
            $table->text('description')->nullable();
            $table->boolean('active')->default(true);
            $table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('suppliers');
	}

}
