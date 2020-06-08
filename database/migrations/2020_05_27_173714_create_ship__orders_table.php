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
            $table->string('name');
            $table->string('product');
            $table->string('street');
            $table->string('number');
            $table->string('code', 15);
            $table->float('lat', 16, 14);
            $table->float('lng', 16, 14);
            $table->string('phone')->nullable($value = true);
            $table->string('description')->nullable($value = true);
            $table->unsignedSmallInteger('status')->default(1);
            $table->unsignedSmallInteger('zone')->nullable($value = true);
            $table->unsignedSmallInteger('neighborhood');
            $table->float('price', 10, 2)->nullable($value = true)->default(0);
            $table->unsignedSmallInteger('route_order')->nullable($value = true)->default(0);
            $table->boolean('is_assigned')->default(0);

            $table->foreign('id_user')->references('id')->on('users');
            $table->foreign('neighborhood')->references('id')->on('neighborhoods');
            $table->foreign('zone')->references('id')->on('zones');

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
