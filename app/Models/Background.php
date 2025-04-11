<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Background extends Model
{
    use HasFactory;
    protected $fillable=['uploads_id','backgroud_img'];

    public function upload(): BelongsTo
    {
        return $this->belongsTo(Upload::class);
    }
}
