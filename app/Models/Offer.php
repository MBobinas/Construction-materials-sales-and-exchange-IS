<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Offer extends Model
{
    use HasFactory;

    protected $fillable = ['wanted_materials', 'offered_materials', 'quantity_wanted', 
                           'quantity_offered', 'measurment_unit', 'status', 'trade_id', 'sender', 
                           'receiver', 'listing_id', 'desired_product_id', 'offered_product_id'];

    public function trades()
    {
        return $this->belongsTo(Trade::class);
    }

}
