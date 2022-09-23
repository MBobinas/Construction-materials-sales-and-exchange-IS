<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransportationOrder extends Model
{
    use HasFactory;

    protected $fillable = ['status', 'order_id', 'trade_id',];

     public function transportationCompany()
    {
        return $this->belongsTo(TransportationCompany::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function trade()
    {
        return $this->belongsTo(Trade::class);
    }

}
