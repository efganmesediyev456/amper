<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Cache;

class City extends BaseModel
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = ['status', 'order'];

    public $casts = [
        'created_at' => 'datetime:Y-m-d H:i:s',
    ];

    public $translatedAttributes = ['title'];

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