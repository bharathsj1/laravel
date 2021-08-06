<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPricesToIngridientTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ingridients', function (Blueprint $table) {
            $table->string('2_person_price')->nullable();
            $table->string('4_person_price')->nullable();
            $table->string('6_person_price')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ingridients', function (Blueprint $table) {
            //
        });
    }
}
