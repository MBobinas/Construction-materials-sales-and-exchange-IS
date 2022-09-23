<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransportationOrderTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transportation_orders', function (Blueprint $table) {
            $table->id();
            //$table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('order_id')->nullable();
            $table->unsignedBigInteger('trade_id')->nullable();
            //$table->unsignedBigInteger('transportation_company_id');
            $table->enum('status', ['laukiama', 'priimta', 'užbaigta', 'atmesta']);
            $table->string('city', 40);
            $table->string('address', 100);
            $table->string('receiver_address', 100);
            $table->string('phone', 15);
            $table->timestamps();

            //$table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');
            $table->foreign('trade_id')->references('id')->on('trades')->onDelete('cascade');
            //$table->foreign('transportation_company_id')->references('id')->on('transportation_companies')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('transportation_order');
    }
}
