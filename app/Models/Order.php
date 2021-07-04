<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;
    protected $guarded=[];

    public function orderDetail()
    {
        return $this->hasMany(OrderDetails::class,'order_id');
    }

    public function customerAddress()
    {
        return $this->belongsTo(UserAddress::class,'id');
    }

}
