<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrderStatus extends Model
{
    use HasFactory;
    use SoftDeletes;

    public $guarded = [];

    public function cancelReason(){
        return $this->belongsTo(OrderCancellationReason::class,'reason_id');
    }
}
