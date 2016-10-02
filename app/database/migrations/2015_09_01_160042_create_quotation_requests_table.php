<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateQuotationRequestsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('quotation_requests', function(Blueprint $table)
		{
			$table->increments('id');
            $table->string('quotation_request_id', 12)->unique();
            $table->integer('created_by_id')->unsigned();
            $table->integer('company_id')->unsigned();
            $table->integer('supplier_id')->unsigned()->nullable();
            $table->boolean('send_to_supplier')->default(false);
            $table->boolean('sent')->default(false);
            $table->text('message')->nullable();
            $table->char('state', 3);
            $table->timestamps();
            $table->integer('updated_by_id')->unsigned()->nullable();

            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
            $table->foreign('created_by_id')->references('id')->on('users');
            $table->foreign('updated_by_id')->references('id')->on('users');
            $table->foreign('supplier_id')->references('id')->on('suppliers');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('quotation_requests');
	}

}
