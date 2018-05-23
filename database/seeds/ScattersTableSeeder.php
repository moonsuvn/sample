<?php

use Illuminate\Database\Seeder;
use App\Models\Scatter;

class ScattersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //生成数据集合
        $scatters = factory(Scatter::class)->times(300)->make();
        Scatter::insert($scatters->toArray());
    }
}
