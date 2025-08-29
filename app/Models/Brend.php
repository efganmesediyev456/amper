<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Cache;

class Brend extends BaseModel
{
    use HasFactory;
    use SoftDeletes;
    
    
    protected $fillable = ['image'];
    
    public $casts = [
        'created_at' => 'datetime:Y-m-d H:i:s',
    ];

    public $translatedAttributes = ['title'];

    public function subCategory(){
        return $this->belongsTo(SubCategory::class,'subcategory_id');
    }

    public function subcategories()
    {
        return $this->belongsToMany(SubCategory::class, 'sub_category_brends', 'brend_id', 'sub_category_id');
    }


    protected static function booted()
    {
        static::created(function ($model) {
            Cache::flush(); 
        });

        static::updated(function ($model) {
            Cache::flush(); 
        });

        static::deleted(function ($model) {
            Cache::flush(); 
        });
    }
}