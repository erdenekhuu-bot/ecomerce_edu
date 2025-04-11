<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Offer extends Model
{
    protected $fillable=['title','start_date','end_date','status','banner','slug'];

    public function offer_products()
    {
        return $this->hasMany(OfferProduct::class);
    }
    public function products()
    {
        return $this->belongsToMany(Product::class, 'offer_products');
    }
}
