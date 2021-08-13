<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Receipe extends Model
{
    use HasFactory;
    protected $guarded=[];

    public function ingridients()
    {
        return $this->hasMany(ReciepeIngridient::class,'receipe_id')->with('ingridient');
    }
}
