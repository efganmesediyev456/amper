<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeliveryAddress extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'city',
        'address',
        'additional_info'
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function city(){
        return $this->belongsTo(City::class);
    }
}
