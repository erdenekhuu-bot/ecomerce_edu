<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Product extends Model
{
    protected $table = "products";
    protected $fillable = [
        'name',
        'slug',
        'description',
        'category_id',
        'price',
    ];
    protected $primaryKey = 'id';
    protected $timestamps = true;
    public function getCategory(): BelongsTo {
        return $this->belongsTo(Category::class, 'category_id', 'id');
    }
}
