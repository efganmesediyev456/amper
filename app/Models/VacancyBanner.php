<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VacancyBanner extends BaseModel
{
    use HasFactory;
   
    protected $fillable = ['image'];

    public $translatedAttributes = ['title', 'description', 'seo_description', 'seo_keywords'];
}