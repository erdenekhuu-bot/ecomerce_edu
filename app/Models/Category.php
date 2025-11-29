<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Category extends Model {
    protected $table = 'categories';
    protected $fillable = ['name', 'description','image'];
    protected $primaryKey = 'id';
    protected $timestamps = true;

    public function getProduct(): HasOne{
        return $this->hasOne(Product::class, 'category_id', 'id');
    }
}
