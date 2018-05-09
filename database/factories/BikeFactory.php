<?php

use Faker\Generator as Faker;



$factory->define(App\Models\Bike::class, function (Faker $faker) {
 


        // 'name' => $faker->name,
        'code' => rand(1000100,1000200),
        'is_riding' => rand(0,1),
    ];
});
