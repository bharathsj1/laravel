<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class userTypes extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        DB::table('account_types')->insert(
            [
                [
                    "id" => 1,
                    "name" => "Super Admin",
                    "status" => 0,

                ],
                [
                    "id" => 2,
                    "name" => "admin",
                    "status" => 1,

                ],
                [
                    "id" => 3,
                    "name" => "user",
                    "status" => 2,

                ],
                [
                    "id" => 4,
                    "name" => "Delivery Boy",
                    "status" => 3,

                ],
                [
                    "id" => 5,
                    "name" => "restaurent_owner",
                    "status" => 4,

                ],
            ]
        );
    }
}
