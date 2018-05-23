<?php

use Faker\Generator as Faker;

$factory->define(App\Models\Scatter::class, function (Faker $faker) {
	$date_time = $faker->date . ' ' . $faker->time;
    $lng=$faker->randomFloat($nbMaxDecimals = NULL, $min = 117.1380000, $max = 117.1670000);
    $lat=$faker->randomFloat($nbMaxDecimals = NULL, $min = 34.2070000, $max = 34.2260000);
    $lnglat=$lng.','.$lat;

    return [
        'lnglat' => $lnglat,
        'created_at' => $date_time,
        'updated_at' => $date_time,
    ];
});
