<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateQuotationsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('quotations', function(Blueprint $table)
		{
			$table->increments('id');
            $table->string('quotation_id', 12)->unique();

            $table->integer('quotation_request_id')->unsigned()->nullable();
            $table->integer('created_by_id')->unsigned();
            $table->integer('company_id')->unsigned();
            $table->integer('supplier_id')->unsigned()->nullable();
            $table->integer('request_id')->unsigned()->nullable();

            // requested data
            $table->text('product_description')->nullable();
            $table->string('uom')->nullable();
            $table->integer('volume')->nullable();
            $table->integer('current_price')->nullable();
            $table->char('current_price_currency', 3)->nullable();

            // received quotation
            $table->date('quotation_date')->nullable();
            $table->string('supplier_reference')->nullable();
            $table->string('suppliers_product_name')->nullable();
            $table->text('suppliers_product_description')->nullable();
            $table->string('suppliers_product_sku')->nullable();
            $table->string('suppliers_uom')->nullable();
            $table->integer('suppliers_quantity')->nullable();
            $table->integer('unit_price')->nullable();
            $table->char('price_currency', 3)->nullable();
            $table->integer('total_price')->nullable();
            $table->date('valid_until')->nullable();
            $table->string('delivery_terms')->nullable();
            $table->string('payment_terms')->nullable();

            $table->char('state', 3);
            $table->boolean('sent')->default(false);
            $table->integer('updated_by_id')->unsigned()->nullable();

            $table->text('remarks')->nullable();
			$table->timestamps();

            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
            $table->foreign('created_by_id')->references('id')->on('users');
            $table->foreign('updated_by_id')->references('id')->on('users');
            $table->foreign('supplier_id')->references('id')->on('suppliers');
            $table->foreign('quotation_request_id')->references('id')->on('quotation_requests');
            $table->foreign('request_id')->references('id')->on('product_requests');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('quotations');
	}

}
