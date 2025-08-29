<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SubCategory extends BaseModel
{
    use SoftDeletes;

    public $casts = [
        'created_at' => 'datetime:Y-m-d H:i:s',
    ];
    public $guarded = [];

    public $translatedAttributes = ['title','slug'];

    public function category(){
        return $this->belongsTo(Category::class);
    }

   public function brends()
    {
        return $this->belongsToMany(Brend::class, 'sub_category_brends', 'sub_category_id', 'brend_id');
    }

}
