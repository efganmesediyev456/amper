<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class VacancyApplication extends BaseModel
{
    use HasFactory;
    use SoftDeletes;
    public $casts = [
        'created_at' => 'datetime:Y-m-d H:i:s',
    ];
    public $guarded = [];

    public function vacancy()
    {
        return $this->belongsTo(Vacancy::class);
    }

    public function recipient()
    {
        return $this->belongsTo(User::class, 'recipient_id');
    }
}
