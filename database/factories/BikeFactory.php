<?php

use Faker\Generator as Faker;



$factory->define(App\Models\Bike::class, function (Faker $faker) {
	$date_time = $faker->date . '' . $faker->time();

	
	return [
        'code'=$faker->numberBetween($min = 1000, $max = 9000),
        'lng'=$faker->randomFloat($nbMaxDecimals = NULL, $min = 0, $max = NULL),
        'lat'=$faker->randomFloat($nbMaxDecimals = NULL, $min = 0, $max = NULL),
        'is_riding'='0',
        'create_at'=>$date_time,
        'update_at'=>$date_time,

	];
 

    
});
