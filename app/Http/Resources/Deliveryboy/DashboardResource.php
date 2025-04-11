<?php

namespace App\Http\Resources\Deliveryboy;

use Illuminate\Http\Resources\Json\JsonResource;

class DashboardResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return parent::toArray($request);


        // return [
        //     'total_collection'=>$this->total_collection,
        //     'total_earning'=>$this->total_earning,
        // ];
    }
}
