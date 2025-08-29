<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Cache;

class Property extends BaseModel
{
    use HasFactory;
    use SoftDeletes;

    public $casts = [
        'created_at' => 'datetime:Y-m-d H:i:s',
    ];

    public $translatedAttributes = ['title'];

    /**
     * Get the sub-properties for this property.
     */
    public function subProperties(): HasMany
    {
        return $this->hasMany(SubProperty::class);
    }

    /**
     * Get the products associated with this property.
     */
    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'product_property')
            ->withTimestamps();
    }

    protected static function booted()
    {
        static::addGlobalScope('orderByIdDesc', function (Builder $builder) {
            $builder->orderBy('id', 'desc');
        });

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
