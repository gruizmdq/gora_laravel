<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {   
        Schema::create('orders', function (Blueprint $table) {
            $table->bigIncrements('id');
            #TODO: check si es necesario esta redundancia;
            $table->string('name');
            $table->unsignedBigInteger('id_user');
            $table->unsignedBigInteger('id_menu');
            $table->unsignedBigInteger('id_delivery');
            $table->date('date');
            $table->text('comments')->nullable($value = true);
            $table->smallInteger('status')->default(0);
            $table->timestamps();


            $table->foreign('id_user')->references('id')->on('users');
            $table->foreign('id_menu')->references('id')->on('menus');
            $table->foreign('id_delivery')->references('id')->on('deliveries');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orders');
    }
}
