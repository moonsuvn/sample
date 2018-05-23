<?php

use Faker\Generator as Faker;

$factory->define(App\Models\Scatter::class, function (Faker $faker) {
	$date_time = $faker->date . ' ' . $faker->time;
    $lng=$faker->randomFloat($nbMaxDecimals = NULL, $min = 116.3580000, $max = 118.6670000);
    $lat=$faker->randomFloat($nbMaxDecimals = NULL, $min = 33.7170000, $max = 34.9760000);
    $lnglat=$lng.','.$lat;

    return [
        'lnglat' => $lnglat,
        'created_at' => $date_time,
        'updated_at' => $date_time,
    ];
});
