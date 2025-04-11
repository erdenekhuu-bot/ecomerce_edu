<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class PriceQuote extends Model
{

    protected $guarded = [];

    public function orderDetails()
    {
        return $this->hasMany(PriceQuoteDetail::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function shop()
    {
        return $this->belongsTo(Shop::class);
    }

    public function commission_histories()
    {
        return $this->hasMany(CommissionHistory::class);
    }

    public function refundRequests()
    {
        return $this->hasMany(RefundRequest::class);
    }

    public function saler()
    {
        return $this->belongsTo(User::class, 'assign_sale_id', 'id');
    }

    public function pickupPoint()
    {
        return $this->belongsTo(PickupPoint::class);
    }

}
