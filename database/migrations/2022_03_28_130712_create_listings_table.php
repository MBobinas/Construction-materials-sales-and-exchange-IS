<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateListingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('listings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('title','60');
            $table->string('slug','60')->unique()->nullable();
            $table->text('description','255');
            $table->decimal('total_sum', 8, 2)->nullable();
            $table->double('ranking', 8, 1)->nullable();
            $table->boolean('isConfirmed')->default(false);
            $table->string('image');
            $table->string('location','30');
            $table->enum('status', ['deaktyvuotas', 'galiojantis', 'negaliojantis','suformuotas']);
            $table->enum('listing_type', ['mainyti', 'parduoti', 'parduoti arba mainyti']);           
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('listings');
    }
}
