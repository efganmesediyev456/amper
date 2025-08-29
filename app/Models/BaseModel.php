<?php

namespace App\Models;

// use App\Models\Activity\Activity;
use App\Traits\AddUuid;
use App\Traits\Status;
use App\Traits\Translation;
use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Models\Activity;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class BaseModel extends Model
{
    use Translation;


    public function scopeStatus($query, $status = true)
    {
        return $query->where('status', $status);
    }


    public function __get($key)
    {
        if (isset($this->translatedAttributes) && in_array($key, $this->translatedAttributes)) {
            return $this->getTranslation($key);
        }

        return parent::__get($key);
    }
}