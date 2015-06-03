<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNewsTranslationsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('news_translations', function(Blueprint $table)
		{
			$table->increments('id');
			
			//Translatable attributes
			$table->string('title');
			$table->text('excerpt');
			$table->text('description');
			$table->string('slug');
		    // Translatable attributes

			$table->integer('news_id')->unsigned()->index();
		    $table->foreign('news_id')->references('id')->on('news')->onDelete('cascade');

		    $table->integer('locale_id')->unsigned()->index();
		    $table->foreign('locale_id')->references('id')->on('locales')->onDelete('cascade');

		    $table->unique(['news_id', 'locale_id']);

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
		Schema::drop('news_translations');
	}

}
