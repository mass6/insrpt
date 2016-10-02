<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSourcingRequestsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('sourcing_requests', function(Blueprint $table)
		{
			$table->increments('id');
            $table->integer('customer_id')->unsigned();
			$table->string('batch')->nullable();
            $table->date('received_on')->nullable();
            $table->string('customer_sku')->index();
            $table->string('customer_product_description');
            $table->string('customer_uom')->nullable();
            $table->integer('customer_price')->nullable();
            $table->string('customer_price_currency')->nullable();
            $table->string('tss_sku')->nullable();
            $table->string('tss_product_name')->nullable();
            $table->string('tss_uom')->nullable();
            $table->integer('tss_buy_price')->nullable();
            $table->string('tss_buy_price_currency')->nullable();
            $table->string('supplier_name')->nullable();
            $table->integer('tss_sell_price')->nullable();
            $table->string('tss_sell_price_currency')->nullable();
            $table->integer('tss_buy_price_margin')->nullable();
            $table->integer('tss_sell_price_margin')->nullable();
            $table->integer('customer_sell_price_margin')->nullable();
            $table->timestamps();
            $table->integer('created_by_id')->unsigned();
            $table->integer('updated_by_id')->unsigned()->nullable();
            $table->char('status',3)->default('ASS');
            $table->integer('assigned_to_id')->unsigned()->nullable();
            $table->timestamp('closed_at')->nullable();
            $table->char('reason_closed',3)->nullable();

            $table->foreign('customer_id')->references('id')->on('companies')->onDelete('cascade');
            $table->foreign('created_by_id')->references('id')->on('users');
            $table->foreign('updated_by_id')->references('id')->on('users');
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
		Schema::drop('sourcing_requests');
	}

}
