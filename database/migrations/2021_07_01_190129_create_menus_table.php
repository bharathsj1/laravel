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
            $table->double('menu_price')->default(0.0);
            $table->string('menu_name')->nullable();
            $table->string('menu_details')->nullable();
            $table->double('menu_quantity')->default(0.0);
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
