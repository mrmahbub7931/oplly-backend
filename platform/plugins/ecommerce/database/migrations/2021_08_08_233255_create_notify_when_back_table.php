<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNotifyWhenBackTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ec_talent_notify_when_back', function (Blueprint $table) {
            $table->integer('talent_id')->unsigned()->default(0);
            $table->integer('user_id')->unsigned()->default(0);
            $table->tinyInteger('was_notify_sent')->default(0);
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
        Schema::dropIfExists('ec_talent_notify_when_back');
    }
}
