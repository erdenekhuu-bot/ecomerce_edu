<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Youtube extends Model
{
    use HasFactory;
    protected $fillable=['url'];

   public function youtube_link()
   {
       return $this->BelongsToMany(Product::class, 'links');
   }
}
