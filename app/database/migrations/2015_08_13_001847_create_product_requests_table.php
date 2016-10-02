<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductRequestsTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_requests', function (Blueprint $table) {
            $table->increments('id');
            $table->string('request_id', 9)->unique();
            $table->integer('list_id')->nullable()->unisgned();
            $table->integer('created_by_id')->unsigned();
            $table->integer('company_id')->unsigned();
            $table->string('product_description');
            $table->string('uom')->nullable();
            $table->string('category')->nullable();
            $table->string('purchase_recurrence')->nullable();
            $table->integer('volume_requested')->unsigned()->nullable();
            $table->string('sku')->nullable();
            $table->integer('current_price')->nullable();
            $table->char('current_price_currency', 3)->nullable();
            $table->string('current_supplier')->nullable();
            $table->text('current_supplier_contact')->nullable();
            $table->string('reference1')->nullable();
            $table->string('reference2')->nullable();
            $table->string('reference3')->nullable();
            $table->string('reference4')->nullable();
            $table->text('remarks')->nullable();
            $table->char('state', 3);
            $table->timestamps();
            $table->softDeletes();
            $table->integer('updated_by_id')->unsigned()->nullable();
            $table->timestamp('closed_at')->nullable();
            $table->char('reason_closed',3)->nullable();

            $table->foreign('created_by_id')->references('id')->on('users');
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
        Schema::drop('product_requests');
    }

}
