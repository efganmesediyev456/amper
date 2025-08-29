<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Language extends Model
{
    use HasFactory;
    public $guarded = [];
    public $casts = [
        'created_at'=>'datetime:Y-m-d H:i:s'
    ];
}
