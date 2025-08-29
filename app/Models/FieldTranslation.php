<?php

namespace App\Models;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FieldTranslation extends BaseModel
{
    use HasFactory;

    public $guarded = [];
    public function model()
    {
        return $this->morphTo();
    }
}