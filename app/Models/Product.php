<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends BaseModel
{
    use HasFactory;
    use SoftDeletes;
    public $casts = [
        'created_at' => 'datetime:Y-m-d H:i:s',
    ];

    public $guarded = [];
    public $translatedAttributes = ['title','seo_keywords','seo_description', 'slug','description'];

    public function media(){
        return $this->hasMany(ProductMedia::class);
    }

    public function category(){
        return $this->belongsTo(Category::class);
    }
    public function subCategory(){
        return $this->belongsTo(SubCategory::class, 'subcategory_id');
    }
    public function brand(){
        return $this->belongsTo(Brand::class);
    }


    public function brend(){
        return $this->belongsTo(Brend::class);
    }


    public function reviews(){
        return $this->hasMany(ProductReview::class);
    }

    public function getImageUrlAttribute()
    {
        if (!$this->image || str_contains($this->image, 'https://placehold.co/600x400')) {
            return asset('/default.webp');
        }

        return url('storage/'.$this->image);
    }



    public function subProperties(): BelongsToMany
    {
        return $this->belongsToMany(SubProperty::class, 'product_sub_property')
            ->withTimestamps();
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }
}
