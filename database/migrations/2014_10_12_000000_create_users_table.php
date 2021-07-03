<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id('id');
            $table->string('cust_first_name')->nullable();
            $table->string('cust_last_name')->nullable();
            $table->string('cust_middle_name')->nullable();
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('cust_profile_image')->nullable();
            $table->string('cust_phone_number')->unique();
            $table->integer('cust_account_status')->default(0);
            $table->integer('cust_registration_type')->default(0)->comment('0 for email,1 for google,2 for apple, 3 for facebook');
            $table->integer('cust_account_type')->default(0);
            $table->string('cust_uid')->unique()->nullable();
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}
