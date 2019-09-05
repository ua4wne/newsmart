<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDevicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('devices', function (Blueprint $table) {
            $table->increments('id');
            $table->char('uid',16)->unique();
            $table->string('name',70);
            $table->text('descr')->nullable();
            $table->string('address',32)->nullable();
            $table->enum('verify',[0,1])->default(0);
            $table->enum('status',[0,1])->default(0);
            $table->integer('protocol_id')->unsigned();
            $table->foreign('protocol_id')->references('id')->on('protocols');
            $table->integer('location_id')->unsigned();
            $table->foreign('location_id')->references('id')->on('locations');
            $table->integer('type_id')->unsigned();
            $table->foreign('type_id')->references('id')->on('device_types');
            $table->string('image',50)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('devices');
    }
}
