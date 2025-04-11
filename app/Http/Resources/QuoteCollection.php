<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class QuoteCollection extends ResourceCollection
{
    public function toArray($request)
    {
        return [
            'data' => $this->collection->map(function($data) {
                return [
                    'id' => $data->id,
                    'code' => $data->quote_no,
                    'user' => [
                        'name' => $data->user->name,
                        'email' => $data->user->email,
                        'phone' => $data->user->phone,
                        'avatar' => api_asset($data->user->avatar),
                    ],
                    'shipping_address' => json_decode($data->shipping_address),
                    'grand_total' => (double) $data->grand_total,
                    'quote_status' => $data->quote_status,
                    'coupon_discount' => $data->coupon_discount,
                    'discount_type' => $data->discount_type,
                    'is_buy' => $data->is_buy,
                    'quote_status' => $data->quote_status,
                    'orders' => QuoteResource::collection([$data]),
                    'date' => $data->created_at->toFormattedDateString()
                ];
            })
        ];
    }

    public function with($request)
    {
        return [
            'success' => true,
            'status' => 200
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
