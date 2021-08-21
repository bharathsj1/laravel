<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNewParamsToOrders extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->string('cutlery')->nullable();
            $table->string('delivery_free')->nullable();
            $table->string('service_fee')->nullable();
            $table->string('restaurent_tip')->nullable();
            $table->string('tip_more')->nullable();
            $table->string('rider_tip')->nullable();
            $table->string('sub_total')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            //
        });
    }
}
