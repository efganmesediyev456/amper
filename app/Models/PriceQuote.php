<?php

// app/Models/PriceQuote.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PriceQuote extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    public $casts = [
        'created_at'=>'datetime:Y-m-d H:m:s',
    ];
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'phone',
        'note',
        'file_path',
    ];
}
