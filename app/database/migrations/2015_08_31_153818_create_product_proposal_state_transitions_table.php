<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductProposalStateTransitionsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('product_proposal_state_transitions', function(Blueprint $table)
		{
			$table->increments('id');
            $table->string('event')->nullable();
            $table->string('from')->nullable();
            $table->string('to');
            $table->integer('product_proposal_id')->unsigned();
            $table->string('product_proposal_type')->nullable();
            $table->string('user_id')->nullable();
            // $table->foreign('my_stateful_model_id')->references('id')->on('my_stateful_models');//Optional foreign key
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
		Schema::drop('product_proposal_state_transitions');
	}

}
