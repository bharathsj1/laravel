<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDeliveryPickupTableServiceToRestaurents extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('restaurents', function (Blueprint $table) {
            $table->boolean('delivery')->default(false);
            $table->boolean('pickup')->default(false);
            $table->boolean('table_service')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('restaurents', function (Blueprint $table) {
            //
        });
    }
}
