<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Trade extends Model
{
    use HasFactory;

    protected $fillable = ['status'];

    public function listing()
    {
        return $this->belongsTo(Listing::class, 'trade_for_listing', 'id');
    }
    
    public function offers()
    {
        return $this->hasMany(Offer::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'offer_sender', 'id');
    }
    
    public function transportationCompany()
    {
        return $this->belongsTo(TransportationCompany::class, 'transportation_company_id', 'id');
    }

    public function transportationOrder()
    {
        return $this->hasOne(TransportationOrder::class);
    }

}
