<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateShoeDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shoe_details', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('id_shoe');
            $table->unsignedBigInteger('id_color');
            $table->unsignedBigInteger('id_number');
            $table->float('price', 10, 2)->nullable($value = false);
            $table->unsignedSmallInteger('stock');

            $table->timestamps();

            $table->foreign('id_shoe')->references('id')->on('shoes');
            $table->foreign('id_color')->references('id')->on('shoe_colors');
            $table->foreign('id_number')->references('id')->on('shoe_numbers');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('shoe_details');
    }
}
