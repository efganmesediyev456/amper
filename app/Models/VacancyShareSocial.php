<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class VacancyShareSocial extends BaseModel
{
    use SoftDeletes;
    use HasFactory;
    
    protected $fillable = ['url', 'image', 'status', 'order'];
    
    protected $casts = [
        'created_at' => 'datetime:Y-m-d H:i:s'
    ];

    public $translatedAttributes = ['title'];
}