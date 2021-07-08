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
        return $this->belongsTo(Order::class)->with('user_address');
    }

    public function customerAddress()
    {
        return $this->belongsTo(UserAddress::class,'id','user_id');

    }

    public function user_address()
    {
        return $this->belongsTo(UserAddress::class, 'customer_addressId','id');
    }



    public function rest_menu()
    {
        return $this->belongsTo(Menu::class,'rest_menuId' ,'id');
    }

    public function restDetails()
    {
        return $this->belongsTo(Restaurents::class,'rest_id','id');

    }

    

}
