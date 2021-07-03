<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class menutypes extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('menu_types')->insert(
            [
                [
                    "id" => 1,
                    "menu_name" => "Indian",
                    "menu_type_image" => "https://cdn.pixabay.com/photo/2017/09/09/12/09/india-2731812_1280.jpg",

                ],
                [
                    "id" => 2,
                    "menu_name" => "Korean",
                    "menu_type_image" => "https://cdn.pixabay.com/photo/2015/02/16/17/59/side-dish-638660_1280.jpg",

                ],
            ]
        );
    }
}
