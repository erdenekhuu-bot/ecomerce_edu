<?php

namespace App\Http\Resources;

use App\Http\Resources\Shipping\PickupPointResource;
use App\Models\PickupPoint;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'code' => $this->code,
            'shop' => [
                'name' => $this->shop->name ?? translate('Shop not found'),
                'slug' => $this->shop->slug ?? '',
            ],
            'payment_type' => $this->payment_type,
            'manual_payment' => $this->manual_payment,
            'manual_payment_data' => $this->manual_payment_data ? json_decode($this->manual_payment_data): null,
            'delivery_type' => $this->delivery_type,
            'type_of_delivery' => $this->type_of_delivery,
            'pickup_point' => new PickupPointResource(PickupPoint::find($this->pickup_point_id)),
            'delivery_status' => $this->delivery_status,
            'payment_status' => $this->payment_status,
            'coupon_discount' => (double) $this->coupon_discount,
            'membership' => (double) $this->membership,
            'discount' => (double) $this->discount,
            'shipping_cost' => (double) $this->shipping_cost,
            'grand_total' => (double) $this->grand_total,
            'qpay_qrimage' => $this->qpay_qrimage,
            'subtotal' => (double) $this->orderDetails->sum('total') - $this->calculateTotalTax($this->orderDetails),
            'tax' => (double) $this->calculateTotalTax($this->orderDetails),
            'products' => new OrderProductCollection($this->orderDetails),
            'created_at' => strtotime($this->created_at),
            'has_refund_request' => $this->refundRequests->count() > 0 ? true : false, 
            'courier_name' => $this->courier_name, 
            'tracking_number' => $this->tracking_number, 
            'tracking_url' => $this->tracking_url, 
        ];
    }

    protected function calculateTotalTax($orderDetails){
        $tax = 0;
        foreach($orderDetails as $item){
            $tax += $item->tax*$item->quantity;
        }
        return $tax;
    }
}
