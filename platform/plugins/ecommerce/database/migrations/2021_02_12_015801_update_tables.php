<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ec_orders', function (Blueprint $table) {
            $table->text('video')->nullable();
        });

        Schema::table('ec_products', function (Blueprint $table) {
            $table->string('product_type')->default('video');
            $table->integer('talent_id')->unsigned()->default(0);
        });

        Schema::table('ec_customers', function (Blueprint $table) {
            $table->text('video')->nullable();
            $table->text('photo')->nullable();
            $table->integer('product_id')->unsigned()->default(0);
            $table->boolean('allows_live')->default(false);
            $table->boolean('allows_chat')->default(false);
            $table->boolean('has_cause')->default(false);
            $table->text('cause_details')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ec_orders', function (Blueprint $table) {
            $table->dropColumn('video');
        });

        Schema::table('ec_products', function (Blueprint $table) {
            $table->dropColumn('video');
            $table->dropColumn('product_type');
        });
    }
}