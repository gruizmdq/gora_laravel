<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrderIndividualItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order__individual__items', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->unsignedBigInteger('id_order');
            $table->unsignedBigInteger('id_menu');
            $table->smallInteger('qty');
            
            $table->foreign('id_order')->references('id')->on('order__individuals');
            $table->foreign('id_menu')->references('id')->on('menus');
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
        Schema::dropIfExists('order__individual__items');
    }
}
