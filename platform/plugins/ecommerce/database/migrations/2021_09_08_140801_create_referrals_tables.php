<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReferralsTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ec_referrals', function (Blueprint $table) {
            $table->id();
            $table->integer('referrer_id')->nullable();
            $table->integer('referee_id')->nullable();
            $table->string('status')->nullable()->default('created');
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
        Schema::dropIfExists('ec_referrals_table');
    }
}
