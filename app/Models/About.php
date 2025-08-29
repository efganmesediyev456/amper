<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class About extends BaseModel
{
    use HasFactory;
   

    public $translatedAttributes = ['title', 'description','seo_description','seo_keywords'];



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
