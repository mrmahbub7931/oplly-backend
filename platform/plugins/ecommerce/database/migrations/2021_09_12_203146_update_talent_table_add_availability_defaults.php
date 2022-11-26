<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateTalentTableAddAvailabilityDefaults extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ec_talent', function (Blueprint $table) {
            $table->tinyInteger('disable_weekends')->default(0);
            $table->tinyInteger('disable_mondays')->default(0);
            $table->tinyInteger('disable_tuesdays')->default(0);
            $table->tinyInteger('disable_wednesdays')->default(0);
            $table->tinyInteger('disable_thursdays')->default(0);
            $table->tinyInteger('disable_fridays')->default(0);
            $table->tinyInteger('disable_saturdays')->default(0);
            $table->string('workday_start')->default();
            $table->string('workday_end')->default();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
