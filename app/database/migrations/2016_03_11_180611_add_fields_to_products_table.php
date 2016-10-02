<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFieldsToProductsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('products', function(Blueprint $table)
		{
			$table->string('type_id');
			$table->string('website');
			$table->boolean('status');
			$table->string('category')->nullable();
			$table->string('supplier')->nullable();
			$table->string('supplier_id')->nullable();
			$table->string('url_key');
			$table->string('image')->nullable();
			$table->string('thumbnail')->nullable();
			$table->string('special_price')->nullable()->after('price');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('products', function(Blueprint $table)
		{
			$table->dropColumn('type_id');
			$table->dropColumn('website');
			$table->dropColumn('status');
			$table->dropColumn('category');
			$table->dropColumn('supplier');
			$table->dropColumn('supplier_id');
			$table->dropColumn('url_key');
			$table->dropColumn('image');
			$table->dropColumn('thumbnail');
			$table->dropColumn('special_price');
		});
	}

}
