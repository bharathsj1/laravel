<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReceipeOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('receipe_orders', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id')->default(0);
            $table->integer('customer_address_id')->default(0);
            $table->integer('receipe_id')->default(0);
            $table->string('amount')->nullable();
            $table->string('payment_intent_id')->nullable();
            $table->string('person_quantity')->nullable();
            $table->string('status')->nullable();
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
        Schema::dropIfExists('receipe_orders');
    }
}
