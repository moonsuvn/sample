<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBikesTable extends Migration 
{
	public function up()
	{
		Schema::create('bikes', function(Blueprint $table) {
            $table->increments('id');
            $table->integer('code')->comment('单车编码');
            $table->float('lng', 10, 7)->comment('经度');
            $table->float('lat', 10, 7)->comment('纬度');
            $table->boolean('is_riding')->default(false)->comment('是否正在被用户骑行');
            $table->timestamps();
        });
	}

	public function down()
	{
		Schema::drop('bikes');
	}
}
