<?php

use App\Domain;
use App\License;
use Illuminate\Database\Seeder;

class LicensesTableSeeder extends Seeder
{
	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		factory( License::class, 10 )
			->create()
			->each( function( $license ) {
				factory( Domain::class, mt_rand( 0, $license->max_domains_allowed ))
					->make()
					->each( function( $domain ) use( $license ) {
						$license->domains()->save( $domain );
					});
			});
	}
}
