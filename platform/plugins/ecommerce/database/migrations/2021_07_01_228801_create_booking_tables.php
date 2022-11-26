<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBookingTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ec_bookings', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamp('start_date')->nullable();
            $table->timestamp('end_date')->nullable();
            $table->string('type', 60)->default('live');
            $table->string('status', 60)->default('available');
            $table->integer('order_id')->unsigned()->default(0);
            $table->integer('talent_id')->unsigned()->default(0);
            $table->timestamps();
        });
        Schema::create('ec_bookings_availability', function (Blueprint $table) {
            $table->id();
            $table->timestamp('start_date')->nullable();
            $table->timestamp('end_date')->nullable();
            $table->timestamp('start_time')->nullable();
            $table->timestamp('end_time')->nullable();
            $table->tinyInteger('all_day')->default(0);
            $table->string('status', 60)->default('available');
            $table->integer('talent_id')->unsigned()->default(0);
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
        Schema::dropIfExists('ec_bookings');
        Schema::dropIfExists('ec_bookings_availability');
    }
}
