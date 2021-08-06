<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReciepeIngridient extends Model
{
    use HasFactory;

    protected $guarded =[];

    public function receipe()
    {
        return $this->belongsTo(Receipe::class);
    }

    public function ingridient()
    {
        return $this->belongsTo(Ingridients::class);
    }
}
