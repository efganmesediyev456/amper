<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserCard extends Model
{
    protected $table = 'user_cards';

    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
