<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReceipeSubscriptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('receipe_subscriptions', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id')->default(0);
            $table->string('person_quantity')->nullable();
            $table->string('subscription_plan_id')->nullable();
            $table->string('subscription_start_date')->nullable();
            $table->string('payment_intent')->nullable();
            $table->string('total_receipes')->nullable();
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
        Schema::dropIfExists('receipe_subscriptions');
    }
}
