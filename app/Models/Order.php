<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'product_id', 'transportation_company_id', 'price', 'quantity', 'paid_at', 'delivered_at', 'status',
        'city', 'address', 'phone', 'more_info', 'transportation_needed'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    public function listing()
    {
        return $this->belongsTo(Listing::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function transportationCompany()
    {
        return $this->belongsTo(TransportationCompany::class);
    }

    public function transportationOrder()
    {
        return $this->hasOne(TransportationOrder::class);
    }
}
