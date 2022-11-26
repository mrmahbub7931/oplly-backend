<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateOccasionsTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ec_occasions', function (Blueprint $table) {
            $table->tinyInteger('show_business')->unsigned()->default(0);
            $table->tinyInteger('show_standard')->unsigned()->default(1);
        });
    }
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ec_occasions', function (Blueprint $table) {
            $table->dropColumn('show_business');
            $table->dropColumn('show_standard');
        });
    }
}
