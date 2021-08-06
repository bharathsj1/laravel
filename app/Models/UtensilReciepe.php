<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UtensilReciepe extends Model
{
    use HasFactory;
    protected $guarded =[];

    public function utensil()
    {
        return $this->belongsTo(Utensils::class);
    }
}
