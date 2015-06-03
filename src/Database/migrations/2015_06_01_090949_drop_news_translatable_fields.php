<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DropNewsTranslatableFields extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('news', function(Blueprint $table)
		{
			$table->dropColumn('title');
			$table->dropColumn('excerpt');
			$table->dropColumn('description');
			$table->dropColumn('slug');
			$table->dropColumn('image');
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
			$table->string('title');
			$table->text('excerpt');
			$table->text('description');
			$table->string('slug');
			$table->string('image');
		});
	}

}
