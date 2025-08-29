<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductMedia extends Model
{
    use HasFactory;
    use SoftDeletes;
    public $guarded = [];


    public function getImageUrlAttribute()
    {
        if (!$this->image || str_contains($this->image, 'https://placehold.co/600x400')) {
            return asset('/default.webp');
        }

        return url('storage/'.$this->image);
    }
}
