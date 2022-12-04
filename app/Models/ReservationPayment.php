<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReservationPayment extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_reserve',
        'payment_method'
    ];

    public function user_reserve() {
        return $this->belongsTo(UserReserve::class, 'user_reserve');
    }
    public function payment() {
        return $this->hasOne(OnlineReserve::class, 'user_reserve');
    }
}
