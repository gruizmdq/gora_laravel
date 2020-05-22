<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrderIndividualsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order__individuals', function (Blueprint $table) {
            $table->bigIncrements('id');

            #TODO: check si es necesario esta redundancia;
            $table->string('name');
            $table->unsignedBigInteger('id_delivery')->nullable($value = true);
            $table->string('address');
            $table->smallInteger('qty');
            $table->smallInteger('shift');
            $table->date('date');
            $table->text('comments')->nullable($value = true);
            $table->smallInteger('status')->default(0);


            $table->foreign('id_delivery')->references('id')->on('deliveries');
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
        Schema::dropIfExists('order__individuals');
    }
}
