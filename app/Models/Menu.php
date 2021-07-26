<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    use HasFactory;
    protected $guarded=[];

    public function foodCategory()
    {
        return $this->belongsTo(FoodCategory::class);

    }

    public function restaurant()
    {
        return $this->belongsTo(Restaurents::class,'rest_id');
    }

    public function menuType()
    {
        return $this->belongsTo(MenuType::class,'menu_type_id');
    }

}
