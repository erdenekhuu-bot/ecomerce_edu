<?php

namespace App\Http\Resources;

use App\Http\Resources\OrderProductCollection;
use Illuminate\Http\Resources\Json\JsonResource;

class QuoteSingleCollection extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {   
        
        return [
            'id' => $this->id,
            'code' => $this->quote_no,
            'user' => [
                'name' => $this->user->name,
                'email' => $this->user->email,
                'phone' => $this->user->phone,
                'avatar' => api_asset($this->user->avatar),
            ],
            'shipping_address' => json_decode($this->shipping_address),
            'billing_address' => json_decode($this->billing_address),
            'grand_total' => (double) $this->grand_total,
            'orders' => QuoteResource::collection([$this]),
            'date' => $this->created_at->toFormattedDateString()
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