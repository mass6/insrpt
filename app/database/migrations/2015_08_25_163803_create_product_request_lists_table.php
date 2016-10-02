<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductRequestListsTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_request_lists', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('created_by_id')->unsigned();
            $table->integer('company_id')->unsigned();
            $table->string('name');
            $table->timestamps();

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
        Schema::drop('product_request_lists');
    }

}
