<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReciepeIngridientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reciepe_ingridients', function (Blueprint $table) {
            $table->id();
            $table->integer('receipe_id')->default(0);
            $table->integer('ingridient_id')->default(0);
            $table->string('two_person_quantity')->nullable();
            $table->string('three_person_quantity')->nullable();
            $table->string('four_person_quantity')->nullable();

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
        Schema::dropIfExists('reciepe_ingridients');
    }
}
