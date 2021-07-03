<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class restaurentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('restaurents')->insert(
            [
               [ "id" => 1,
                "rest_address" => "Royal Avenue",
                "rest_name" => "Habibi",
                "rest_latitude" => 25.34,
                "rest_longitude" => 75.33,
                "rest_isTrending" => 0,
                "rest_status" => "1",
                "rest_image" => "https://media.timeout.com/images/103793299/750/422/image.jpg",
                "rest_type" => "pakistani",
                "rest_zipCode" => "90000",
                "rest_isOpen" => 1,
                "rest_openTime" => "09=>00",
                "rest_close_time" => "11=>00",
                "rest_phone" => "04223230421",
                "rest_country" => "Pakistan",
                "rest_menuId" => 1,
                "rest_city" => "Islamabad",],
                [
                    "id" => 2,
                    "rest_address" => "Habib Street",
                    "rest_name" => "Butt",
                    "rest_latitude" => 23.23,
                    "rest_longitude" => 78.12,
                    "rest_isTrending" => 0,
                    "rest_status" => "1",
                    "rest_image" => "https://media.timeout.com/images/103793299/750/422/image.jpg",
                    "rest_type" => "mexican",
                    "rest_zipCode" => "12338",
                    "rest_isOpen" => 0,
                    "rest_openTime" => "08:00",
                    "rest_close_time" => "12:00",
                    "rest_phone" => "09880087",
                    "rest_country" => "Pakistan",
                    "rest_menuId" => 1,
                    "rest_city" => "Islamabad",
      
                ],
                
            ],
            
        );
    }
}
