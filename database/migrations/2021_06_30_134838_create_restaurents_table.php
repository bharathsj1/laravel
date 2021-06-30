<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRestaurentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('restaurents', function (Blueprint $table) {
            $table->id();
            $table->string('address')->nullable();
            $table->string('name')->nullable();
            $table->double('lat')->default(0.0);
            $table->double('lng')->default(0.0);
            $table->boolean('is_trending')->default(false);
            $table->string('status')->nullable();
           
            $table->string('image')->nullable();
            $table->string('type')->nullable();
            $table->string('zipcode')->nullable();
            $table->boolean('open')->default(false);
            $table->string('open_time')->nullable();
            $table->string('close_time')->nullable();
            $table->string('phone')->nullable();
            $table->string('country')->nullable();
            $table->string('city')->nullable();
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
        Schema::dropIfExists('restaurents');
    }
}
