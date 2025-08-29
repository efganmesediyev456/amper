<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChangeEmailOtp extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'otp',
        'expires_at',
        'is_used',
        'new_email'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
