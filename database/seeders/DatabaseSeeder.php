<?php

namespace Database\Seeders;

use App\Models\MenuType;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            menu::class,
            menutypes::class,
            restaurentSeeder::class,
            userTypes::class,
        ]);
    
    }
}
