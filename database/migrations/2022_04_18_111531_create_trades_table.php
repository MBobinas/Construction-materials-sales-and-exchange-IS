<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTradesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('trades', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->unsignedBigInteger('offer_id');
            $table->unsignedBigInteger('listing_id');
            $table->unsignedBigInteger('offer_sender');
            $table->unsignedBigInteger('offer_receiver');
            $table->unsignedBigInteger('transportation_company_id');
            $table->string('wanted_materials', 255);
            $table->string('offered_materials', 255);
            $table->string('email', 100);
            $table->string('phone', 40)->nullable();
            $table->string('city', 40)->nullable();
            $table->string('address', 100)->nullable();
            $table->enum('status', ['laukiama', 'priimta', 'atšaukta', 'užbaigta']);

            $table->foreign('offer_id')->references('id')->on('offers')->onDelete('cascade');
            $table->foreign('listing_id')->references('id')->on('listings')->onDelete('cascade');
            $table->foreign('offer_sender')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('offer_receiver')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('transportation_company_id')->references('id')->on('transportation_companies')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('trades');
    }
}
