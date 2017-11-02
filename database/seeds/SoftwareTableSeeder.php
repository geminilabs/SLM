<?php

use App\Software;
use Illuminate\Database\Seeder;

class SoftwareTableSeeder extends Seeder
{
	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		factory( Software::class )->create([
			'name' => 'Site Reviews - Tripadvisor',
			'slug' => 'site_reviews_tripadvisor',
			'repository' => 'https://bitbucket.org/geminilabs/site-reviews-tripadvisor',
			'product_id' => '13',
			'status' => 'active',
		]);
	}
}
