<?php

namespace App\Http\Resources\Deliveryboy;

use Illuminate\Http\Resources\Json\JsonResource;

class TotalCollectionResource extends JsonResource
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
            'id'=>$this->id,
            'code'=>$this->order->combined_order->code,
            'date'=> date('d-m-Y h:i A', strtotime($this->created_at)),
            'amount'=> $this->collection
        ];
    }
}
