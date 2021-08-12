<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function orderDetail()
    {
        return $this->hasMany(OrderDetails::class, 'order_id')->with(['rest_menu','restDetails']);
    }

    public function customerAddress()
    {
        return $this->belongsTo(UserAddress::class, 'customer_addressId','id');
    }

    public function user_address()
    {
        return $this->belongsTo(UserAddress::class, 'customer_addressId');
    }

    public function rest_menu()
    {
        return $this->belongsTo(MenuType::class,'id');
    }

    public function receipe()
    {
        return $this->belongsTo(Receipe::class,'receipe_id');
    }

    public function review()
    {
        return $this->belongsTo(ratings::class,'id','order_id');
    }

    

}
