<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OnlineReserve extends Model
{
    use HasFactory;
    protected $fillable = [
        'reservation_payment',
        'user_reserve',
        'payment_status',
        'reference',
        'image',
        'temp_name',
    ];
    public function reservation_payment() {
        return $this->belongsTo(UserReserve::class, 'user_reserve');
    }

}
