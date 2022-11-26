<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateBookingsTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ec_bookings', function (Blueprint $table) {
            $table->string('meeting_url', 255)->default();
        });
        Schema::table('ec_bookings_availability', function (Blueprint $table) {
            $table->tinyInteger('is_recurring')->default(0);
            $table->string('period', 60)->default('day');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ec_bookings', function (Blueprint $table) {
            $table->dropColumn('meeting_url');
        });
        Schema::table('ec_bookings_availability', function (Blueprint $table) {

            $table->dropColumn('is_recurring');
            $table->dropColumn('period');
        });
    }
}
