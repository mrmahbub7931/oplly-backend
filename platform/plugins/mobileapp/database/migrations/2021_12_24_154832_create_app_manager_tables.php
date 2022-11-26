<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateAppManagerTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ec_app_settings', function (Blueprint $table) {
            $table->id();
            $table->string('platform', 120);
            $table->string('version', 120);
            $table->json('homepage');
            $table->json('homepage_talent');
            $table->unsignedTinyInteger('allow_push')->default(1);
            $table->unsignedTinyInteger('allow_feed')->default(1);
            $table->unsignedTinyInteger('allow_live')->default(1);
            $table->unsignedTinyInteger('allow_causes')->default(1);
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
        Schema::dropIfExists('ec_app_settings');
    }
}
