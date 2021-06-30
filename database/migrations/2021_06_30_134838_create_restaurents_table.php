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
            $table->double('ratings')->default(0.0);
            $table->boolean('is_trending')->default(false);
            $table->string('status')->nullable();
            $table->integer('sub_category')->default(-1);
            $table->integer('rest_id')->default(-1);
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
