<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{

    protected $guarded = [];

    public function orderDetails()
    {
        return $this->hasMany(OrderDetail::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function shop()
    {
        return $this->belongsTo(Shop::class);
    }

    public function combined_order()
    {
        return $this->belongsTo(CombinedOrder::class);
    }

    public function commission_histories()
    {
        return $this->hasMany(CommissionHistory::class);
    }

    public function order_udpates()
    {
        return $this->hasMany(OrderUpdate::class)->latest();
    }

    public function refundRequests()
    {
        return $this->hasMany(RefundRequest::class);
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function delivery_boy()
    {
        return $this->belongsTo(User::class, 'assign_delivery_boy', 'id');
    }
    public function pickupPoint()
    {
        return $this->belongsTo(PickupPoint::class);
    }

    public function saler()
    {
        return $this->belongsTo(User::class, 'assign_sale_id', 'id');
    }
}
