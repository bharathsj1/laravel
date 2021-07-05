<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderDetails extends Model
{
    use HasFactory;
    protected $guarded=[];

    public function order()
    {
        return $this->belongsTo(Order::class)->with('customerAddress');
    }

    public function customerAddress()
    {
        return $this->belongsTo(UserAddress::class,'id','user_id');

    }



    public function rest_menu()
    {
        return $this->belongsTo(MenuType::class,'rest_menuId' ,'id');
    }

    

}
