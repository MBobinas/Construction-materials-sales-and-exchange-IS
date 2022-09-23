<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOffersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('offers', function (Blueprint $table) {
            $table->id();
            $table->string('wanted_materials');
            $table->string('offered_materials');
            $table->integer('quantity_wanted');
            $table->integer('quantity_offered')->nullable();
            $table->enum('measurment_unit', ['vnt', 'kg', 'pakuočių', 'litrų']);
            $table->unsignedBigInteger('trade_id');
            $table->unsignedBigInteger('sender');
            $table->unsignedBigInteger('receiver');
            $table->unsignedBigInteger('listing_id');
            $table->unsignedBigInteger('desired_product_id');
            $table->unsignedBigInteger('offered_product_id')->nullable();
            $table->timestamps();

            $table->foreign('trade_id')->references('id')->on('trades');
            $table->foreign('sender')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('receiver')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('listing_id')->references('id')->on('listings')->onDelete('cascade');
            $table->foreign('desired_product_id')->references('id')->on('products')->onDelete('cascade');
            $table->foreign('offered_product_id')->references('id')->on('products')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('offers');
    }
}
