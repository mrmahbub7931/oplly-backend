<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEcTalentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ec_talent', function (Blueprint $table) {
            $table->id();
            $table->string('first_name');
            $table->string('last_name')->nullable();
            $table->text('bio')->nullable();
            $table->string('phone', 25)->nullable();
            $table->integer('customer_id')->unsigned()->default(0);
            $table->string('email')->nullable();
            $table->text('video')->nullable();
            $table->string('dob')->nullable();
            $table->text('photo')->nullable();
            $table->string('gender')->default('');
            $table->integer('main_product_id')->unsigned()->default(0);
            $table->boolean('allows_live')->default(false);
            $table->integer('live_product_id')->unsigned()->default(0);
            $table->boolean('allows_chat')->default(false);
            $table->integer('chat_product_id')->unsigned()->default(0);
            $table->boolean('has_cause')->default(false);
            $table->text('verify_video')->nullable();
            $table->text('cause_details')->nullable();
            $table->string('status')->default('created');
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
        Schema::dropIfExists('ec_talents');
    }
}