<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    public $casts = [
        "created_at"=>"datetime:Y-m-d H:i:s"
    ];

    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'phone',
        'delivery_type',
        'total_amount',
        'status',
        'store_id'
    ];

    public function deliveryAddress()
    {
        return $this->hasOne(DeliveryAddress::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function store()
    {
        return $this->hasOne(Store::class);
    }

    public function statusesHistories(){
        return $this->hasMany(OrderStatus::class)->withTrashed();
    }

    public function status(){
        return $this->hasOne(OrderStatus::class);
    }

    public function user(){
        return $this->belongsTo(User::class);
    }


    protected static function boot()
    {
        parent::boot();

        static::creating(function ($order) {
            $lastOrderNumber = self::max('order_number');
            $order->order_number = $lastOrderNumber ? ((int)$lastOrderNumber + 1) : 1000000;
        });
    }

}
