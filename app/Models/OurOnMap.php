<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OurOnMap extends BaseModel
{
    use HasFactory;
    public $casts = [
        'created_at' => 'datetime:Y-m-d H:i:s',
    ];

    public $guarded = [];
    public $translatedAttributes = ['title','seo_keywords','seo_description', 'slug','description','work_hours','address'];

    public function media(){
        return $this->hasMany(ProductMedia::class);
    }
}
