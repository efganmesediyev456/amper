<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class WeeklySelection extends BaseModel
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'title',
        'start_date',
        'end_date',
        'status',
        'order'
    ];

    public $casts = [
        'created_at' => 'datetime:Y-m-d H:i:s',
        'start_date' => 'datetime:Y-m-d H:i:s',
        'end_date' => 'datetime:Y-m-d H:i:s',
        'status' => 'integer'
    ];

    public $translatedAttributes = ['title'];

    public function products()
    {
        return $this->belongsToMany(Product::class, 'weekly_selection_products');
    }
}