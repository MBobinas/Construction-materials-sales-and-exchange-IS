<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransportationCompany extends Model
{
    use HasFactory;
    
    protected $fillable = ['name', 'service_fee', 'description'];

    public function listings()
    {
        return $this->hasMany(Listing::class);
    }

    public function orders()
    {
        return $this->hasMany(TransportationOrder::class);
    }

    public function offers()
    {
        return $this->hasMany(Offer::class);
    }

    public function transportationOrders()
    {
        return $this->hasMany(TransportationOrder::class);
    }
}
