<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Listing;

class Product extends Model
{
    use HasFactory;
    protected $fillable = ['product_name', 'description', 'price', 'image', 'quantity',
                            'measurment_unit', 'condition','listing_id','category_id'];

    public function listings()
    {
        return $this->belongsToMany(Listing::class);
    }

    public function getRouteKeyName()
    {
        return 'product_name';
    }

    public function category()
    {
        return $this->belongsTo('App\Models\MaterialCategory');
    }

    public function orders()
    {
        return $this->belongsToMany(Order::class);
    }

}
