<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateShipOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ship__orders', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('id_user');
            $table->string('street');
            $table->string('number');
            $table->float('lat', 16, 14);
            $table->float('lng', 16, 14);
            $table->string('phone')->nullable($value = true);
            $table->unsignedSmallInteger('status')->default(0);
            $table->unsignedSmallInteger('zone')->nullable($value = true);
            $table->unsignedSmallInteger('neighborhood');
            $table->float('price', 10, 2)->default(0);
            $table->foreign('id_user')->references('id')->on('users');

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
        Schema::dropIfExists('ship__orders');
    }
}
