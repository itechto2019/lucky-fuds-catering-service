<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserRent extends Model
{
    use HasFactory;
    protected $table = "user_rents";
    protected $fillable = [
        'user_id',
        'stock_id',
        'for_rent_id',
        'amount',
        'date',
        'return',
        'status',
        'is_returned'
    ];
    public function info() {
        return $this->belongsTo(UserInfo::class, "user_id");
    }
    public function for_rent() {
        return $this->belongsTo(ForRent::class);
    }
}
