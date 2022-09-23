<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Product;

class Listing extends Model
{
    use HasFactory;
    protected $table = 'listings';
    protected $fillable = [
        'user_id',
        'title',
        'description',
        'total_sum',
        'ranking',
        'isConfirmed',
        'image',
        'location',
        'status',
        'listing_type'
    ];

    protected $attributes = [
        'isConfirmed' => 0,
        'status' => "suformuotas",
     ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function products()
    {
        return $this->belongsToMany(Product::class);
    }

    public function trades()
    {
        return $this->hasMany(Trade::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}
