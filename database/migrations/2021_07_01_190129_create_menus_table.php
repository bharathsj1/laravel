<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMenusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('menus', function (Blueprint $table) {
            $table->id();
            $table->string('menu_image')->nullable();
            $table->string('menu_price')->nullable();
            $table->string('menu_name')->nullable();
            $table->string('menu_details')->nullable();
            $table->string('menu_quantity')->nullable();
            $table->integer('rest_id')->default(-1);
            $table->integer('menu_type_id')->default(-1);
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
        Schema::dropIfExists('menus');
    }
}
