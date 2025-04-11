<?php

namespace App\Http\Resources;
use App\Models\Membership;
use Illuminate\Http\Resources\Json\ResourceCollection;

class CartCollection extends ResourceCollection
{
    public function toArray($request)
    {
        return [
            'data' => $this->collection->map(function($data) {
                $membership_price = (double) variation_price($data->product,$data->variation);
                $discount_price = (double) variation_discounted_price($data->product,$data->variation);
                $user = auth('api')->user();
                if ($user) {
                    if ($user->user_type == "customer") {
                        $total_order_price = $user->orders()->where('payment_status','paid')->sum('grand_total');
                        $memberships = Membership::orderby('value', 'ASC')->get();
                        foreach ($memberships as $key => $membership) {
                            if ($membership->value <= $total_order_price) {
                                $membership_price -= ($membership_price / 100) * $membership->percent;
                                $discount_price -= ($discount_price / 100) * $membership->percent;
                            }
                        }
                    }
                }
                return [
                    'cart_id' => (integer) $data->id,
                    'product_id' => (integer) $data->product_id,
                    'shop_id' => (integer) $data->product->shop_id,
                    'earn_point' => (float) $data->product->earn_point,
                    'variation_id' => (integer) $data->product_variation_id,
                    'name' => $data->product->name,
                    'combinations' => filter_variation_combinations($data->variation->combinations),
                    'thumbnail' => $data->variation->img ? api_asset($data->variation->img) : api_asset($data->product->thumbnail_img),
                    'regular_price' => $membership_price,
                    'dicounted_price' => $discount_price,
                    'tax' => (double) product_variation_tax($data->product,$data->variation),
                    'stock' => (integer) $data->variation->stock,
                    'min_qty' => (integer) $data->product->min_qty,
                    'max_qty' => (integer) $data->product->max_qty,
                    'standard_delivery_time' => (integer) $data->product->standard_delivery_time,
                    'express_delivery_time' => (integer) $data->product->express_delivery_time,
                    'qty' => (integer) $data->quantity,
                    'is_digital'=> $data->product->digital,
                    'for_pickup'=> $data->product->for_pickup,
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
}
