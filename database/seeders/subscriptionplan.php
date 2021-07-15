<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class subscriptionplan extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('subscription_plans')->insert([
            [
                'id'=>1,
                'plan_details'=>'100% free',
                'discount'=>'100',
                'status'=>'active',
                'duration'=>'30',
                'price'=>'500'

            ],
            [
                'id'=>2,
                'plan_details'=>'50% free',
                'discount'=>'50',
                'status'=>'active',
                'duration'=>'30',
                'price'=>'400'

            ],

            [
                'id'=>3,
                'plan_details'=>'20% free',
                'discount'=>'20',
                'status'=>'active',
                'duration'=>'30',
                'price'=>'200'

            ]
        ]);
    }
}
