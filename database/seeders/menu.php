<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class menu extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('menus')->insert([[
            "id" => 1,
            "menu_name"=> "Indian Burger",
            "menu_image"=> "https://cdn.pixabay.com/photo/2016/03/05/19/02/abstract-1238247_1280.jpg",
            'menu_price'=>200,
            'menu_details'=> 'Ground turkey, bread crumbs',
            'menu_quantity'=>200,
            'rest_id'=>1,
            'menu_type_id'=>1,
           
        ],
        [
            "id"=> 2,
            "menu_image"=> "https://images.pexels.com/photos/1624487/pexels-photo-1624487.jpeg?cs=srgb&dl=pexels-rajesh-tp-1624487.jpg&fm=jpg",
            "menu_price"=> 100,
            "menu_name"=> "Biryani",
            "menu_details"=> "Spicy Biryani",
            "menu_quantity"=> 100,
            "rest_id"=> 2,
            "menu_type_id"=> 2,
           
        ],]
   
    );
    }
}
