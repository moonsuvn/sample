<?php

use Illuminate\Database\Seeder;
use App\Models\Bike;

class BikesTableSeeder extends Seeder
{
	
    public function run()
    {

    	//生成数据集合
        $bikes = factory(Bike::class)->times(1000)->make();
        Bike::insert($bikes->toArray());    
        
    }

}

