<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCategoryIdAndIsFeaturedToNewsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		
		Schema::table('news', function(Blueprint $table)
		{
			$table->boolean('is_featured')->default(0);
			$table->integer('category_id')->unsigned();
			$table->foreign('category_id')
						->references('id')
						->on('categories')
						->onDelete('cascade');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('news', function(Blueprint $table)
		{	
			$table->dropColumn('is_featured');
			$table->dropColumn('category_id');
		});
	}

}
