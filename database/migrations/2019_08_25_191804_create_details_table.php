<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('details', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('card_id')->unsigned();
            $table->bigInteger('hit')->unsigned()->default(0);
            $table->string('main_image');
            $table->string('main_profile');
            $table->string('name');
            $table->string('job');
            $table->string('address');
            $table->string('phone');
            $table->string('message');
            $table->string('email');
            $table->string('cafe');
            $table->string('facebook');
            $table->string('twitter');
            $table->string('instagram');
            $table->string('band');
            $table->string('kakao');
            $table->string('ad_image_top');
            $table->string('ad_content_top');
            $table->string('ad_image_middle');
            $table->string('ad_content_middle');
            $table->string('ad_image_bottom');
            $table->string('ad_content_bottom');
            $table->timestamps();

            $table->foreign('card_id')->references('id')->on('cards')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('details');
    }
}
