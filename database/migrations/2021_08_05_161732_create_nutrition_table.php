<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNutritionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('nutrition', function (Blueprint $table) {
            $table->id();
            $table->string('Energy (kJ)')->nullable();
            $table->string('Energy (kcal)')->nullable();
            $table->string('fat')->nullable();
            $table->string('of_which_saturates')->nullable();
            $table->string('carbohydrates')->nullable();
            $table->string('of_which_sugars')->nullable();
            $table->string('dietary_fiber')->nullable();
            $table->string('protein')->nullable();
            $table->string('cholestrerol')->nullable();
            $table->string('salt')->nullable();
            $table->boolean('per_serving')->default(true);
            $table->integer('receipe_id')->default(0);
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
        Schema::dropIfExists('nutrition');
    }
}
