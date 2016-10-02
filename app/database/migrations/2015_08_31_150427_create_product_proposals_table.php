<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductProposalsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('product_proposals', function(Blueprint $table)
		{
			$table->increments('id');
            $table->string('proposal_id', 12)->unique();
            $table->integer('request_id')->unsigned();
            $table->integer('created_by_id')->unsigned();
            $table->integer('company_id')->unsigned();
            $table->integer('quotation_id')->unsigned()->nullable();

            $table->string('product_name');
            $table->string('sku')->nullable();
            $table->text('product_description')->nullable();
            $table->string('uom')->nullable();
            $table->integer('volume')->nullable();
            $table->integer('price')->nullable();
            $table->char('price_currency', 3)->nullable();
            $table->string('supplier')->nullable();
            $table->text('supplier_contact')->nullable();
            $table->boolean('display_quotation_details')->default(false);
            $table->text('remarks')->nullable();
            $table->integer('num_approvals')->default(0);
            $table->char('state', 3);
            $table->timestamps();
            $table->softDeletes();
            $table->integer('updated_by_id')->unsigned()->nullable();
            $table->integer('assigned_to_id')->unsigned()->nullable();

            $table->foreign('created_by_id')->references('id')->on('users');
            $table->foreign('request_id')->references('id')->on('product_requests');
            $table->foreign('assigned_to_id')->references('id')->on('users');
        });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('product_proposals');
	}

}
