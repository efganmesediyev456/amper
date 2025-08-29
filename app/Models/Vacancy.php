<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Translatable\HasTranslations;

class Vacancy extends BaseModel
{
    use SoftDeletes;
    use HasFactory;


    protected $casts = [
        'created_at' => 'datetime:Y-m-d H:i:s',
        'updated_at' => 'datetime:Y-m-d H:i:s',
        'status' => 'integer',
        'order' => 'integer',
        'vacany_start_at' => 'datetime:Y-m-d H:i:s',
        'vacany_expired_at' => 'datetime:Y-m-d H:i:s'
    ];

    public $guarded = [];
    public $translatedAttributes = ['vacancy_title', 'vacancy_location', 'seo_keywords', 'seo_description', 'slug', 'description'];

    public function applications()
    {
        return $this->hasMany(VacancyApplication::class);
    }

    public function incrementViews()
    {
        $this->increment('view');
        return $this;
    }
}
