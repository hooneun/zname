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
            $table->string('job')->nullable();
            $table->string('address')->nullable();
            $table->string('phone')->nullable();
            $table->string('message')->nullable();
            $table->string('email')->nullable();
            $table->string('cafe')->nullable();
            $table->string('facebook')->nullable();
            $table->string('twitter')->nullable();
            $table->string('instagram')->nullable();
            $table->string('band')->nullable();
            $table->string('kakao')->nullable();
            $table->string('ad_image_top')->nullable();
            $table->string('ad_content_top')->nullable();
            $table->string('ad_image_middle')->nullable();
            $table->string('ad_content_middle')->nullable();
            $table->string('ad_image_bottom')->nullable();
            $table->string('ad_content_bottom')->nullable();
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
