<?php

use Illuminate\Database\Seeder;
use App\Models\Bike;

class BikesTableSeeder extends Seeder
{
	
    public function run()
    {

        $faker = app(Faker\Generator::class);

    
    	//生成数据集合
        $bikes = factory(Bike::class)
                 ->times(10)
                 ->make()
                 ->each(function ($bike, $index) 
        use ($bikes,$faker)
        {
            
        });

        Bike::insert($bikes->toArray());
    }

}

