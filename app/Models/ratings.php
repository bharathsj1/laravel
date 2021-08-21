<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ratings extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function likes()
    {
        return $this->hasMany(Likes::class, 'review_id')->with('user');
    }
    public function comments()
    {
        return $this->hasMany(Comment::class, 'review_id')->with('user');
    }
    public function restaurent()
    {
        return $this->belongsTo(Restaurents::class, 'rest_id');
    }
}
