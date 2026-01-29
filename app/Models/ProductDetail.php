<?php

namespace App\Models;
use App\Models\Product;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Model;

class ProductDetail extends Model
{
    protected $table = 'product_details';
    protected $primaryKey = 'id';
    protected $fillable = [
        'id',
        'product_id',
        'size',
        'color',
        'total',
        'additional_info',
        'stock',
        'rate',
    ];
    protected $timestamps = true;

    public function product(): HasOne
    {
        return $this->hasOne(Product::class, 'product_id', 'id');
    }
    
}
