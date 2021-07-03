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
            $table->string('rest_address')->nullable();
            $table->string('rest_name')->nullable();
            $table->double('rest_latitude')->default(0.0);
            $table->double('rest_longitude')->default(0.0);
            $table->boolean('rest_isTrending')->default(false);
            $table->string('rest_status')->nullable();
           
            $table->string('rest_image')->nullable();
            $table->string('rest_type')->nullable();
            $table->string('rest_zipCode')->nullable();
            $table->boolean('rest_isOpen')->default(false);
            $table->string('rest_openTime')->nullable();
            $table->string('rest_close_time')->nullable();
            $table->string('rest_phone')->nullable();
            $table->string('rest_country')->nullable();
            $table->integer('rest_menuId')->default(-1);
            $table->string('rest_city')->nullable();
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
