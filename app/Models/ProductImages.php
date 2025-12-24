<?php

namespace App\Models;
use App\Models\Product;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProductImages extends Model
{
    protected $table = 'product_images';
    protected $primaryKey = 'id';
    protected $fillable = [
        'id',
        'product_id',
        'image1',
        'image2',
        'image3',
        'image4',
    ];
    protected $timestamps = true;

    public function product(): HasMany
    {
        return $this->hasMany(Product::class, 'product_id', 'id');
    }
}
