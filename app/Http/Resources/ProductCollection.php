<?php

namespace App\Http\Resources;
use App\Models\Membership;
use Illuminate\Http\Resources\Json\ResourceCollection;

class ProductCollection extends ResourceCollection
{
    public function toArray($request)
    {
        return [
            'data' => $this->collection->map(function($data) {
                $membership_price = (double) product_base_price($data);
                $user = auth('api')->user();
                if ($user) {
                    if ($user->user_type == "customer") {
                        $total_order_price = $user->orders()->where('payment_status','paid')->sum('grand_total');
                        $memberships = Membership::orderby('value', 'ASC')->get();
                        foreach ($memberships as $key => $membership) {
                            if ($membership->value <= $total_order_price) {
                                $membership_price -= ($membership_price / 100) * $membership->percent;
                            }
                        }
                    }
                }
                return [
                    'id' => (integer) $data->id,
                    'name' => $data->getTranslation('name'),
                    'slug' => $data->slug,
                    'thumbnail_image' => api_asset($data->thumbnail_img),
                    'base_price' => (double) $membership_price,
                    'base_discounted_price' => (double) product_discounted_base_price($data),
                    'stock' => $data->stock,
                    'unit' => $data->getTranslation('unit'),
                    'min_qty' => $data->min_qty,
                    'max_qty' => $data->max_qty,
                    'product_status' => $data->product_status,
                    'order_detail' => $data->order_detail,
                    'rating' => (double) $data->rating,
                    'earn_point' => (float) $data->earn_point,
                    'is_variant' => (int) $data->is_variant,
                    'variations' => $data->variations,
                    'is_digital' => $data->digital == 1 ? true : false,

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