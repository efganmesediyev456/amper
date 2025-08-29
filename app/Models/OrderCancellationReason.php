<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrderCancellationReason extends BaseModel
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = ['status', 'order'];

    public $casts = [
        'created_at' => 'datetime:Y-m-d H:i:s',
        'status' => 'integer'
    ];

    public $translatedAttributes = ['title'];

}
