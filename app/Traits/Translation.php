<?php

namespace App\Traits;

use App\Models\FieldTranslation as ModelsFieldTranslation;
use App\Models\Gopanel\FieldTranslation;
use Illuminate\Database\Eloquent\Builder;

trait Translation
{



    public function translations()
    {
        return $this->morphMany(ModelsFieldTranslation::class,'model');
    }

    public function getTranslation($attribute, $locale = null)
    {
        $locale = $locale ?: app()->getLocale();
        $translation = $this->translations()?->where('key', $attribute)?->where('locale', $locale)?->first();
        return $translation ? $translation?->value : null;
    }

    public function __get($key)
    {
        if (in_array($key, $this->translatedAttributes)) {
            return $this->getTranslation($key);
        }

        return parent::__get($key);
    }

   

}