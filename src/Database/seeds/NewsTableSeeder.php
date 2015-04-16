<?php

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

use App\Models\News;
use App\Models\Seo;
use Carbon\Carbon;

class NewsTableSeeder extends Seeder {

	/**
	 * Description
	 * @return type
	 */
	public function run()
	{	
		$this->cleanUp();
		$faker = Faker::create();
		$dates = [ Carbon::now(), Carbon::now()->addDays(4), Carbon::now()->subWeeks(1), Carbon::now()->tomorrow(), Carbon::now()->yesterday(), Carbon::now()->subDays(3) ];
		
		for($i=0;$i<15;$i++)
		{
			$title = $faker->sentence();
			$index = array_rand($dates);
			$published_at = $dates[$index];
			
			$news = News::create([
				'title' => $title,
				'excerpt' => $faker->paragraph(3),
				'description' => $faker->text(450),
				'slug' => str_slug($title, '-'),
				'published_at' => $published_at
			]);
			
			$seo = new Seo;
			$news->seo()->save($seo);
		}
	}

	/**
	 * truncates the table before seeding
	 * @return type
	 */
	private function cleanUp()
	{
		DB::statement('SET FOREIGN_KEY_CHECKS = 0');
		DB::table('news')->truncate();
		DB::statement('SET FOREIGN_KEY_CHECKS = 1');
	}

}
