<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BlogNew extends BaseModel
{
    use HasFactory;
    use SoftDeletes;
    public $casts = [
        'created_at' => 'datetime:Y-m-d H:i:s',
        'date' => 'datetime',
    ];

    public $guarded = [];
    public $translatedAttributes = ['title','seo_keywords','seo_description', 'slug','description','subtitle'];

    public function getImageUrlAttribute()
    {
        if (!$this->image) {
            return asset('/default.webp');
        }

        return url('storage/'.$this->image);
    }
}
