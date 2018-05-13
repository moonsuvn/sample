<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRidersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('riders', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('user_id')->index()->comment('用户ID');
            $table->unsignedInteger('bike_id')->index()->comment('单车ID');
            $table->float('start_lng', 10, 7)->nullable()->comment('开始经度');
            $table->float('start_lat', 10, 7)->nullable()->comment('开始纬度');
            $table->float('end_lng', 10, 7)->nullable()->comment('结束经度');
            $table->float('end_lat', 10, 7)->nullable()->comment('结束纬度');
            $table->dateTime('start_at')->nullable()->comment('开始骑行时间');
            $table->dateTime('end_at')->nullable()->comment('结束时间');
            $table->unsignedInteger('money')->nullable()->comment('金额');
            $table->boolean('is_pay')->default(false)->comment('是否已经支付');
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('bike_id')->references('id')->on('bikes');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('riders');
    }
}
